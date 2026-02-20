<?php

namespace App\Http\Controllers;

use App\Events\GirarRuleta;
use App\Events\OverlayReset;
use App\Events\GameBonusUpdated;
use App\Models\GameSession;
use App\Models\GuestAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * API para Stream Deck vía Bitfocus Companion.
 * Rutas bajo /sd/* (sin CSRF, autenticadas con STREAMDECK_TOKEN).
 */
class StreamDeckController extends Controller
{
    // ─── Helpers ────────────────────────────────────────────────────────────

    private function session(): ?GameSession
    {
        return GameSession::where('status', 'active')->latest()->first();
    }

    private function spinningKey(int $sessionId): string
    {
        return 'sd_spinning_' . $sessionId;
    }

    // ─── Estado (Companion lo pollea cada segundo) ───────────────────────────

    /**
     * GET /sd/state
     * Devuelve el estado actual del juego como JSON.
     * Companion usa esto para actualizar las teclas dinámicamente.
     */
    public function state()
    {
        $session = $this->session();

        $spinning         = false;
        $apuestaDisp      = 0;
        $apuestaActive    = false;
        $descarteDisp     = false;
        $questionCount    = 0;
        $guestName        = '';
        $guestPoints      = 0;
        $sessionActive    = false;

        if ($session) {
            $sessionActive = true;
            $guestName     = $session->guest_name ?? '';
            $guestPoints   = $session->guest_points ?? 0;
            $spinning      = (bool) Cache::get($this->spinningKey($session->id), false);
            $limite        = $session->isExpress() ? 1 : 2;
            $apuestaActive = (bool) $session->apuesta_x2_active;
            $apuestaDisp   = max(0, $limite - (int) $session->apuesta_x2_usadas);
            $descarteDisp  = (int) $session->descarte_usados < 1;
            $questionCount = GuestAnswer::where('game_session_id', $session->id)->count();
        }

        return response()->json([
            // Estado de sesión
            'session_active'      => $sessionActive,
            'guest_name'          => $guestName,
            'guest_points'        => $guestPoints,

            // Ruleta
            'spinning'            => $spinning,
            'ruleta_label'        => $spinning ? 'PARAR' : 'GIRAR',

            // Apuesta
            'apuesta_active'      => $apuestaActive,
            'apuesta_disponibles' => $apuestaDisp,
            'apuesta_label'       => $apuestaActive
                ? 'APUESTA ON'
                : ($apuestaDisp > 0 ? "APUESTA x{$apuestaDisp}" : 'AGOTADA'),

            // Descarte
            'descarte_disponible' => $descarteDisp,
            'descarte_label'      => $descarteDisp ? 'DESCARTE' : 'AGOTADO',

            // Preguntas
            'question_count'      => $questionCount,
            'question_limit'      => 15,
            'question_label'      => "{$questionCount}/15",
            'limit_reached'       => $questionCount >= 15,
        ]);
    }

    // ─── Acciones ────────────────────────────────────────────────────────────

    /**
     * POST /sd/ruleta
     * Alterna entre girar y parar la ruleta.
     */
    public function ruleta()
    {
        $session = $this->session();
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa'], 422);
        }

        $key        = $this->spinningKey($session->id);
        $spinning   = (bool) Cache::get($key, false);
        $newSpinning = !$spinning;

        Cache::put($key, $newSpinning, now()->addHours(3));
        broadcast(new GirarRuleta());

        Log::info('[STREAMDECK] Ruleta toggled', ['spinning' => $newSpinning]);

        return response()->json([
            'success' => true,
            'spinning' => $newSpinning,
            'label'    => $newSpinning ? 'PARAR' : 'GIRAR',
        ]);
    }

    /**
     * POST /sd/revelar
     * Revela la respuesta de la pregunta activa.
     */
    public function revelar(Request $request)
    {
        $session = $this->session();
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa'], 422);
        }

        if (!$session->pregunta_json) {
            return response()->json(['error' => 'No hay pregunta activa'], 422);
        }

        // Inyectar datos de pregunta en sesión PHP para que revealAnswer funcione
        session(['last_overlay_question' => json_decode($session->pregunta_json, true)]);

        // Recuperar opción seleccionada vía Companion (guardada en Cache, no en sesión PHP)
        $cachedOption = Cache::get('sd_selected_option_' . $session->id);
        if ($cachedOption) {
            session(['selected_guest_option' => $cachedOption]);
            Cache::forget('sd_selected_option_' . $session->id);
            Log::info("[STREAMDECK] Opción inyectada desde cache: {$cachedOption}");
        }

        Log::info('[STREAMDECK] Revelar disparado');

        return app(GameSessionController::class)->revealAnswer($request);
    }

    /**
     * POST /sd/refrescar
     * Resetea el overlay, limpia pregunta_json en BD y limpia estado de ruleta.
     */
    public function refrescar(Request $request)
    {
        $session = $this->session();
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa'], 422);
        }

        // Limpiar spinning y opción seleccionada pendiente del Cache
        Cache::forget($this->spinningKey($session->id));
        Cache::forget('sd_selected_option_' . $session->id);

        Log::info('[STREAMDECK] Overlay reseteado');

        // Delegar a overlayReset para limpiar pregunta_json y active_question_id en BD
        return app(GameSessionController::class)->overlayReset($request);
    }

    /**
     * POST /sd/apuesta
     * Activa o desactiva la apuesta x2.
     */
    public function apuesta(Request $request)
    {
        Log::info('[STREAMDECK] Apuesta toggled');
        return app(GameBonusController::class)->toggleApuestaX2($request);
    }

    /**
     * POST /sd/descarte
     * Usa el descarte disponible.
     */
    public function descarte(Request $request)
    {
        Log::info('[STREAMDECK] Descarte usado');
        return app(GameBonusController::class)->toggleDescarte($request);
    }

    /**
     * POST /sd/opcion/{label}
     * Selecciona la respuesta del invitado (A, B, C o D).
     * Guarda en Cache (no en sesión PHP) para sobrevivir entre requests stateless.
     */
    public function opcion(Request $request, string $label)
    {
        $label = strtoupper($label);

        if (!in_array($label, ['A', 'B', 'C', 'D'])) {
            return response()->json(['error' => 'Opción inválida. Debe ser A, B, C o D.'], 422);
        }

        $session = $this->session();
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa'], 422);
        }

        // Guardar en Cache para que /sd/revelar lo recupere en el siguiente request
        Cache::put('sd_selected_option_' . $session->id, $label, now()->addHours(1));

        // Broadcast del evento para que el overlay/panel vean la selección en tiempo real
        broadcast(new \App\Events\OpcionSeleccionada($label));

        Log::info("[STREAMDECK] Opción seleccionada y guardada en cache: {$label}");

        return response()->json(['ok' => true, 'opcion' => $label]);
    }
}
