<?php

namespace App\Services;

use App\Models\ParticipantAnswer;
use Illuminate\Support\Facades\Log;

class GamePointsService
{
    public static function calcularPuntaje($participantSessionId, $gameSessionId = null)
    {
        Log::info("[PUNTAJE] Iniciando cálculo para participantSessionId={$participantSessionId}, gameSessionId={$gameSessionId}");

        $query = ParticipantAnswer::where('participant_session_id', $participantSessionId);

        if ($gameSessionId) {
            $query->whereHas('participantSession', function($q) use ($gameSessionId) {
                $q->where('game_session_id', $gameSessionId);
            });
        }

        $answers = $query->orderBy('id')->get();
        Log::info("[PUNTAJE] Respuestas recuperadas: " . $answers->count());

        // Solo la última respuesta por pregunta
        $uniqueAnswers = $answers
            ->groupBy('question_id')
            ->map(function($group) {
                return $group->last(); // La respuesta más reciente por pregunta
            })
            ->values();

        Log::info("[PUNTAJE] Respuestas únicas (últimas por pregunta): " . $uniqueAnswers->count());

        $puntaje = 0;
        $detalles = [];

        foreach ($uniqueAnswers as $respuesta) {
            $correctLabel = $respuesta->label_correcto;
            if (!$correctLabel) {
                Log::warning("[PUNTAJE] Respuesta sin label_correcto (question_id={$respuesta->question_id})");
                continue;
            }

            $esCorrecta = $respuesta->option_label === $correctLabel;

            Log::info("[PUNTAJE] pregunta_id={$respuesta->question_id}, tu_respuesta={$respuesta->option_label}, correcta={$correctLabel}, ES_CORRECTA=" . ($esCorrecta ? 'SI' : 'NO'));

            // AJUSTE: suma 1 si es correcta, 0 si no
            $puntaje += $esCorrecta ? 1 : 0;
            $detalles[] = [
                'pregunta_id' => $respuesta->question_id,
                'correcta' => $esCorrecta,
                'puntaje' => $esCorrecta ? 1 : 0,
            ];
        }

        $puntajeFinal = max($puntaje, 0);
        Log::info("[PUNTAJE] Puntaje sin tope: {$puntaje}, Puntaje devuelto (>=0): {$puntajeFinal}");

        return [
            'total' => $puntajeFinal,
            'detalles' => $detalles,
        ];
    }
    public static function calcularPuntajeInvitado($invitadoId, $sessionId)
{
    // Obtenemos todas las respuestas del invitado
    $answersInvitado = ParticipantAnswer::where('participant_session_id', $invitadoId)
        ->whereHas('participantSession', function($q) use ($sessionId) {
            $q->where('game_session_id', $sessionId);
        })
        ->orderBy('id')
        ->get()
        ->groupBy('question_id')
        ->map(function($group) {
            return $group->last();
        })
        ->values();

    $puntaje = 0;
    $detalles = [];

    foreach ($answersInvitado as $respuestaInvitado) {
        $questionId = $respuestaInvitado->question_id;
        $correctLabel = $respuestaInvitado->label_correcto;
        $opcionInvitado = $respuestaInvitado->option_label;

        // Buscar respuesta del participante activo para esa pregunta
        // Suponiendo que guardás el id del participante actual en sesión...
        $participantSessionId = session('participant_session_id'); // O pasalo por parámetro si lo necesitas
        $respuestaPublico = ParticipantAnswer::where('participant_session_id', $participantSessionId)
            ->where('question_id', $questionId)
            ->latest('id')
            ->first();

        $opcionPublico = $respuestaPublico ? $respuestaPublico->option_label : null;

        // Si es pregunta de oro (puede estar indicado en el label o un campo especial)
        // Si tenés alguna forma de marcarlo en la pregunta, poné la lógica acá
        $isPreguntaOro = false;
        // Ejemplo: if ($respuestaInvitado->question && $respuestaInvitado->question->es_pregunta_oro) { ... }
        // O verificá un campo especial

        if ($isPreguntaOro) {
            $puntaje += 5;
            $detalles[] = [
                'pregunta_id' => $questionId,
                'tipo' => 'oro',
                'puntaje' => 5,
            ];
            continue;
        }

        // Ahora tus reglas:
        $aciertaInvitado = $opcionInvitado === $correctLabel;
        $aciertaPublico  = $opcionPublico === $correctLabel;

        if ($aciertaInvitado && $aciertaPublico) {
            $puntaje += 2;
            $detalles[] = ['pregunta_id' => $questionId, 'regla' => 'ambos_aciertan', 'puntaje' => 2];
        } elseif ($aciertaInvitado && !$aciertaPublico) {
            $puntaje += 3;
            $detalles[] = ['pregunta_id' => $questionId, 'regla' => 'invitado_solo', 'puntaje' => 3];
        } elseif (!$aciertaInvitado && $aciertaPublico) {
            $puntaje -= 1;
            $detalles[] = ['pregunta_id' => $questionId, 'regla' => 'publico_solo', 'puntaje' => -1];
        } else {
            $puntaje -= 1;
            $detalles[] = ['pregunta_id' => $questionId, 'regla' => 'ninguno_acierta', 'puntaje' => -1];
        }
    }

    return [
        'total' => max($puntaje, 0), // nunca menor a 0
        'detalles' => $detalles,
    ];
}

}
