<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GameSessionController;
use App\Models\GameSession;
use App\Models\ParticipantSession;
use Illuminate\Http\Request;

/**
 * Login de participantes para la app mobile (username + últimos 4 del DNI, sin contraseña —
 * mismo esquema que /participants/add en la web). Emite un token Sanctum propio de
 * ParticipantSession, independiente del guard de sesión/cookie que usa la web y del
 * login de host/admin (User). Ambos clientes escriben la misma tabla `participant_sessions`,
 * así que un jugador puede estar anotado por web y por mobile sin conflicto.
 */
class ParticipantAuthController extends Controller
{
    public function join(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:30',
            'dni_last4' => 'required|digits:4',
        ]);

        $session = GameSession::where('status', 'active')->latest()->first();
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa.'], 422);
        }

        $participant = ParticipantSession::where('game_session_id', $session->id)
            ->where('username', $validated['username'])
            ->where('dni_last4', $validated['dni_last4'])
            ->first();

        if (!$participant) {
            $participant = app(GameSessionController::class)
                ->createParticipant($session, $validated['username'], $validated['dni_last4']);
        }

        $token = $participant->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'participant' => [
                'id' => $participant->id,
                'username' => $participant->username,
                'game_session_id' => $participant->game_session_id,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Elimina por completo al participante (mismo comportamiento destructivo que
     * /salir-juego en la web: borra sus respuestas y su fila de la cola).
     */
    public function leave(Request $request)
    {
        $participant = $request->user();
        $sessionId = $participant->game_session_id;

        \App\Models\ParticipantAnswer::where('participant_session_id', $participant->id)->delete();
        $participant->tokens()->delete();
        $participant->delete();

        if ($sessionId) {
            broadcast(new \App\Events\ParticipantQueueUpdated($sessionId));
        }

        return response()->json(['ok' => true]);
    }
}
