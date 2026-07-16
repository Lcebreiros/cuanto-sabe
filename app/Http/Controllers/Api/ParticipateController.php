<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GameSessionController;
use App\Models\GameSession;
use App\Models\ParticipantAnswer;
use App\Services\GamePointsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Equivalente JSON del flujo /participar de la web, para la app mobile. Ambos leen y
 * escriben las mismas tablas (game_sessions.pregunta_json, participant_answers), así
 * que un jugador conectado por web y otro por mobile compiten en la misma partida.
 */
class ParticipateController extends Controller
{
    /**
     * Pregunta activa + si este participante ya respondió + su puntaje actual.
     * Equivalente JSON de GameSessionController::participar().
     */
    public function state(Request $request)
    {
        $participant = $request->user();
        $session = GameSession::where('status', 'active')->latest()->first();

        $data = null;
        if ($session && $session->pregunta_json) {
            $data = json_decode($session->pregunta_json, true);
            // No pasar la respuesta correcta antes del reveal (mismo criterio que la web).
            unset($data['label_correcto']);
        }

        $yaRespondio = null;
        if (isset($data['pregunta_id'])) {
            $yaRespondio = ParticipantAnswer::where('participant_session_id', $participant->id)
                ->where('question_id', $data['pregunta_id'])
                ->first();
        }

        $puntaje = 0;
        if ($session) {
            $puntajesMap = app(GamePointsService::class)
                ->calcularPuntajesParticipantes([$participant->id], $session->id);
            $puntaje = $puntajesMap[$participant->id] ?? 0;
        }

        return response()->json([
            'pregunta' => $data,
            'sin_pregunta' => !$data,
            'ya_respondio' => $yaRespondio?->option_label,
            'puntaje' => ['total' => $puntaje],
            'participant' => [
                'id' => $participant->id,
                'username' => $participant->username,
            ],
        ]);
    }

    /**
     * Envía la respuesta del participante. Reusa el mismo núcleo que /participar/enviar
     * (GameSessionController::submitParticipantAnswer) para no duplicar la protección
     * de race condition ni el cálculo de tendencia de votos.
     */
    public function answer(Request $request)
    {
        $request->validate([
            'option_label' => 'required|in:A,B,C,D',
            'question_id' => 'required|exists:questions,id',
        ]);

        $error = app(GameSessionController::class)->submitParticipantAnswer(
            $request->user(),
            (int) $request->question_id,
            $request->option_label
        );

        if ($error) {
            return response()->json(['error' => $error], 409);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Borra la respuesta previa del participante a una pregunta, si todavía no fue
     * revelada. Equivalente JSON de GameSessionController::resetParticipante().
     */
    public function resetAnswer(Request $request)
    {
        $request->validate(['question_id' => 'required|exists:questions,id']);
        $participant = $request->user();

        $revealedKey = 'revealed_question_' . $participant->game_session_id . '_' . $request->question_id;
        if (Cache::has($revealedKey)) {
            return response()->json(['error' => 'Esta pregunta ya fue revelada, no se puede modificar la respuesta'], 409);
        }

        ParticipantAnswer::where('participant_session_id', $participant->id)
            ->where('question_id', $request->question_id)
            ->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Recupera "¿acerté? ¿cuál es mi puntaje?" para una pregunta puntual, por si la app
     * se perdió el broadcast de reveal en tiempo real (background, reconexión, etc.).
     */
    public function result(Request $request)
    {
        $request->validate(['question_id' => 'required|exists:questions,id']);
        $participant = $request->user();
        $questionId = (int) $request->question_id;

        $session = GameSession::where('status', 'active')->latest()->first();
        $isRevealed = $session && Cache::has('revealed_question_' . $session->id . '_' . $questionId);

        $answer = ParticipantAnswer::where('participant_session_id', $participant->id)
            ->where('question_id', $questionId)
            ->first();

        $puntaje = 0;
        if ($session) {
            $puntajesMap = app(GamePointsService::class)
                ->calcularPuntajesParticipantes([$participant->id], $session->id);
            $puntaje = $puntajesMap[$participant->id] ?? 0;
        }

        return response()->json([
            'is_revealed' => $isRevealed,
            'mi_respuesta' => $answer?->option_label,
            'label_correcto' => $isRevealed ? $answer?->label_correcto : null,
            'acerte' => $isRevealed && $answer ? $answer->option_label === $answer->label_correcto : null,
            'puntaje' => ['total' => $puntaje],
        ]);
    }
}
