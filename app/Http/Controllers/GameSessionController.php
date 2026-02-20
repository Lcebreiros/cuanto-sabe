<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameSession;
use App\Models\Question;
use App\Events\NuevaPreguntaOverlay;
use App\Events\OpcionSeleccionada;
use App\Events\ParticipantQueueUpdated;
use App\Models\ParticipantSession;
use App\Models\Motivo;
use App\Models\Categoria;
use Illuminate\Support\Facades\Cookie;
use App\Models\ParticipantAnswer;
use Illuminate\Support\Facades\DB;
use App\Services\GamePointsService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GameSessionController extends Controller
{
        protected function getActiveSessionCached($ttlSeconds = 8)
    {
        return Cache::remember("game_session_active", $ttlSeconds, function () {
            return GameSession::where('status', 'active')->latest()->first();
        });
    }
    public function start(Request $request)
{
    $request->validate([
        'guest_name' => 'required|string|max:50',
        'motivo_id' => 'required|exists:motivos,id',
        'modo_juego' => 'required|in:normal,express',
    ]);

    GameSession::where('status', 'active')->update(['status' => 'ended']);

    $session = GameSession::create([
        'guest_name' => $request->guest_name,
        'motivo_id' => $request->motivo_id,
        'status' => 'active',
        'modo_juego' => $request->modo_juego,
        // inicializar contadores explícitamente
        'apuesta_x2_active' => false,
        'apuesta_x2_usadas' => 0,
        'descarte_usados' => 0,
    ]);

    \Cache::forget('game_session_active');

    $modoTexto = $session->isExpress() ? 'Express (10 pts)' : 'Normal (25 pts)';
    return redirect()->back()->with('success', "¡Juego iniciado para {$session->guest_name} en modo {$modoTexto}!");
}

    public function end()
{
    // Encuentra la sesión activa
    $sessions = GameSession::where('status', 'active')->get();
    foreach ($sessions as $session) {
        // Borra todas las respuestas de los participantes de esa sesión
        $participantIds = $session->participants()->pluck('id');
        \App\Models\ParticipantAnswer::whereIn('participant_session_id', $participantIds)->delete();
        // Borra los participantes
        \App\Models\ParticipantSession::whereIn('id', $participantIds)->delete();
    }
    GameSession::where('status', 'active')->update(['status' => 'ended']);
    return redirect()->back()->with('success', 'Juego finalizado y participantes eliminados.');
}

public function cambiarModo(Request $request, $id)
{
    $request->validate([
        'modo_juego' => 'required|in:normal,express',
    ]);

    $gameSession = GameSession::findOrFail($id);

    $gameSession->modo_juego = $request->modo_juego;
    $gameSession->save();

    // Ejemplo de uso de los helpers para lógica adicional
    if ($gameSession->isExpress()) {
        // lógica específica para modo express
    } elseif ($gameSession->isNormal()) {
        // lógica específica para modo normal
    }

    return response()->json([
        'message' => 'Modo de juego actualizado con éxito',
        'data' => $gameSession
    ]);
}


public function revealAnswer(Request $request)
{
    try {
        $data = session('last_overlay_question', null);
        \Log::info('🔴 REVEAL: Sesión PHP', ['data' => $data]);
        
        if (!$data) {
            $session = $this->getActiveSessionCached(5);
            if ($session && $session->pregunta_json) {
                $data = json_decode($session->pregunta_json, true);

                // ✅ VALIDAR ESTRUCTURA DEL JSON DECODIFICADO
                if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
                    \Log::error('🔴 REVEAL: JSON inválido', [
                        'error' => json_last_error_msg(),
                        'json' => $session->pregunta_json
                    ]);
                    return response()->json(['error' => 'Datos de pregunta corruptos'], 500);
                }

                // ✅ VALIDAR CAMPOS REQUERIDOS
                if (!isset($data['pregunta_id'], $data['label_correcto'], $data['opciones'])) {
                    \Log::error('🔴 REVEAL: JSON con estructura incompleta', ['data' => $data]);
                    return response()->json(['error' => 'Estructura de pregunta inválida'], 400);
                }

                \Log::info('🟡 REVEAL: Recuperado de BD', ['data' => $data]);
                session(['last_overlay_question' => $data]);
            }
        }

        if (!$data) {
            \Log::warning('🔴 REVEAL: No hay pregunta activa');
            return response()->json(['error' => 'No hay pregunta activa en sesión'], 400);
        }

        $session = $this->getActiveSessionCached(5);
        if (!$session) {
            return response()->json(['error' => 'No hay sesión activa'], 400);
        }

        $gamePoints = app(\App\Services\GamePointsService::class);

        $delta = 0;
        $tendencia = null;
        $esOro = false;
        $bonoEspecial = null;

        // Detectar modo especial a partir del indicador guardado en la pregunta
        $specialIndicator = strtolower($data['special_indicator'] ?? '');
        if ($specialIndicator === 'pregunta de oro') {
            $esOro = true;
        } elseif (str_contains($specialIndicator, 'solo yo') || str_contains($specialIndicator, 'ahora yo')) {
            $bonoEspecial = 'ahora_yo';
        } elseif (str_contains($specialIndicator, 'responde el chat')) {
            $bonoEspecial = 'confio';
        }

        // 1. Request body (panel envía la opción directamente — sin race conditions)
        $selectedOption = null;
        $requestOption = $request->input('selected_option');
        if ($requestOption && in_array(strtoupper($requestOption), ['A', 'B', 'C', 'D'])) {
            $selectedOption = strtoupper($requestOption);
            \Log::info('[REVEAL] Opción leída desde request body: ' . $selectedOption);
        }

        // 2. PHP session (fallback para reveals desde el mismo browser sin body)
        if (!$selectedOption) {
            $selectedOption = session('selected_guest_option', null);
            if ($selectedOption) {
                \Log::info('[REVEAL] Opción leída desde PHP session: ' . $selectedOption);
            }
        }

        // 3. Cache (fallback para StreamDeck — requests stateless sin sesión PHP compartida)
        if (!$selectedOption) {
            $cachedOption = Cache::get('sd_selected_option_' . $session->id);
            if ($cachedOption) {
                $selectedOption = $cachedOption;
                \Log::info('[REVEAL] Opción leída desde Cache: ' . $selectedOption);
            }
        }

        // Limpiar siempre el Cache para evitar que la opción se reutilice en la próxima pregunta
        Cache::forget('sd_selected_option_' . $session->id);

        if ($selectedOption && isset($data['label_correcto'], $data['pregunta_id'])) {
            \Log::info('========================================');
            \Log::info('💾 GUARDAR RESPUESTA INVITADO');
            \Log::info('Selected Option: ' . $selectedOption);
            \Log::info('Data completo:', $data);
            \Log::info('Modo especial detectado: esOro=' . ($esOro ? 'true' : 'false') . ', bonoEspecial=' . ($bonoEspecial ?? 'null'));
            \Log::info('========================================');

            // Calcular puntaje invitado
            $delta = $gamePoints->calcularPuntajeInvitado(
                $session->id,
                $selectedOption,
                $data['pregunta_id'],
                $data['label_correcto'],
                $esOro,
                false,
                $bonoEspecial
            );

            // Guardar respuesta del invitado en guest_answers
            $isCorrect = (strtoupper($selectedOption) === strtoupper($data['label_correcto']));

            // Buscar el texto de la opción seleccionada
            $selectedOptionText = null;
            if (isset($data['opciones'])) {
                \Log::info('🔍 Buscando texto de opción', [
                    'selected_option' => $selectedOption,
                    'opciones' => $data['opciones']
                ]);

                foreach ($data['opciones'] as $opcion) {
                    \Log::info('Comparando: ' . strtoupper($opcion['label']) . ' === ' . strtoupper($selectedOption));
                    if (strtoupper($opcion['label']) === strtoupper($selectedOption)) {
                        $selectedOptionText = $opcion['texto'];
                        \Log::info('✅ Texto encontrado', ['texto' => $selectedOptionText]);
                        break;
                    }
                }

                if (!$selectedOptionText) {
                    \Log::warning('⚠️ No se encontró el texto de la opción seleccionada');
                }
            } else {
                \Log::warning('⚠️ $data[opciones] no existe - Keys disponibles: ' . implode(', ', array_keys($data)));
            }

            \App\Models\GuestAnswer::create([
                'game_session_id' => $session->id,
                'question_id' => $data['pregunta_id'],
                'selected_option' => strtoupper($selectedOption),
                'selected_option_text' => $selectedOptionText,
                'correct_option' => strtoupper($data['label_correcto']),
                'is_correct' => $isCorrect,
                'points_awarded' => (int)$delta,
            ]);

            // ✅ ACTUALIZAR PUNTOS DEL INVITADO CON PROTECCIÓN DE RACE CONDITION
            DB::transaction(function () use ($session, $delta) {
                $s = GameSession::where('id', $session->id)->lockForUpdate()->first();
                $s->guest_points = ($s->guest_points ?? 0) + (int)$delta;
                $s->save();
            });

            // Refrescar instancia para obtener valores actualizados
            $session = $session->fresh();

            // Emitir evento para actualizar puntaje en vivo
            broadcast(new \App\Events\GuestPointsUpdated($session->id, $session->guest_points));

            session()->forget('selected_guest_option');

        } elseif (!empty($data['pregunta_id'])) {
            // Sin opción seleccionada: registrar la pregunta igualmente para el contador
            $yaRegistrada = \App\Models\GuestAnswer::where('game_session_id', $session->id)
                ->where('question_id', $data['pregunta_id'])
                ->exists();
            if (!$yaRegistrada) {
                \App\Models\GuestAnswer::create([
                    'game_session_id'      => $session->id,
                    'question_id'          => $data['pregunta_id'],
                    'selected_option'      => null,
                    'selected_option_text' => null,
                    'correct_option'       => strtoupper($data['label_correcto'] ?? ''),
                    'is_correct'           => false,
                    'points_awarded'       => 0,
                ]);
                \Log::info('[REVEAL] Pregunta registrada sin respuesta del invitado (para contador)');
            }
        }

        // ✅ CALCULAR TENDENCIAS — siempre, independientemente de si el invitado respondió
        // El flag en Cache protege contra doble conteo si se presiona revelar dos veces
        if (!empty($data['pregunta_id'])) {
            $tendenciaCacheKey = 'tendencia_contada_' . $session->id . '_' . $data['pregunta_id'];
            $tendenciaYaContada = Cache::has($tendenciaCacheKey);

            if (!$tendenciaYaContada) {
                Cache::put($tendenciaCacheKey, true, now()->addHours(3));
                $tendencia = $gamePoints->calcularTendencia($session->id, $data['pregunta_id']);

                // En "Ahora yo" o "Pregunta de oro" el público no responde, no se cuentan tendencias
                if ($tendencia && $tendencia['option'] && $bonoEspecial !== 'ahora_yo' && !$esOro) {
                    $tendenciaAcierta = (strtoupper($tendencia['option']) === strtoupper($data['label_correcto']));

                    if ($tendenciaAcierta) {
                        $session->incrementarTendenciasAcertadas();
                        $session = $session->fresh();
                        \Log::info('✅ TENDENCIA ACERTADA', [
                            'tendencias_acertadas' => $session->tendencias_acertadas,
                            'tendencias_objetivo'  => $session->tendencias_objetivo,
                            'restantes'            => $session->tendenciasRestantes()
                        ]);
                    } else {
                        \Log::info('❌ TENDENCIA FALLIDA', [
                            'tendencia' => $tendencia['option'],
                            'correcta'  => $data['label_correcto']
                        ]);
                    }
                } elseif ($bonoEspecial === 'ahora_yo') {
                    \Log::info('🔒 AHORA YO: tendencia no contabilizada');
                }
            } else {
                \Log::info('[REVEAL] Tendencia ya contada para esta pregunta, saltando.');
            }
        }

        // ✅ PUNTAJES DE PARTICIPANTES - INDIVIDUAL
        $participantIds = $session->participants()->pluck('id')->toArray();
        $puntajes = $gamePoints->calcularPuntajesParticipantes($participantIds, $session->id);

        // ✅ GUARDAR PUNTAJES EN BASE DE DATOS CON PROTECCIÓN DE RACE CONDITION
        foreach ($puntajes as $participantId => $puntajeTotal) {
            DB::transaction(function () use ($participantId, $puntajeTotal) {
                $participant = \App\Models\ParticipantSession::where('id', $participantId)
                    ->lockForUpdate()
                    ->first();

                if ($participant) {
                    $participant->puntaje = $puntajeTotal;
                    $participant->save();
                }
            });

            \Log::info('💾 Puntaje guardado en BD (con lock)', [
                'participant_id' => $participantId,
                'puntaje' => $puntajeTotal
            ]);
        }

        // ✅ DISPARAR EVENTO INDIVIDUAL PARA CADA PARTICIPANTE CON MANEJO DE ERRORES
        foreach ($puntajes as $participantId => $puntajeTotal) {
            try {
                \Log::info('📤 Enviando PuntajeActualizado', [
                    'participant_id' => $participantId,
                    'puntaje' => $puntajeTotal,
                    'canal' => 'puntaje.' . $participantId
                ]);

                broadcast(new \App\Events\PuntajeActualizado($participantId, $puntajeTotal));
            } catch (\Throwable $e) {
                \Log::error('❌ Error al broadcast puntaje individual', [
                    'participant_id' => $participantId,
                    'error' => $e->getMessage()
                ]);
                // Continuar con el siguiente participante
            }
        }

        // Racha del público
        $rachaPublico = $gamePoints->verificarTendenciaPublico($session->id);

        // Verificar victoria del invitado
        $victoria = $gamePoints->verificarVictoriaInvitado($session->id);

        // ✅ Refrescar sesión para obtener valores actualizados
        $session = $session->fresh();

        // Conteo real de preguntas respondidas por el invitado
        $questionCount = \App\Models\GuestAnswer::where('game_session_id', $session->id)->count();
        $questionLimit = 15;

        // ✅ Broadcast general con toda la data necesaria y MANEJO DE ERRORES
        try {
            broadcast(new \App\Events\RevealAnswerOverlay([
                'pregunta_id' => $data['pregunta_id'],
                'label_correcto' => $data['label_correcto'],
                'opciones' => $data['opciones'] ?? [],
                'pregunta' => $data['pregunta'] ?? '',
                'delta_invitado' => $delta,
                'puntaje_invitado' => $session->guest_points,
                'puntajes_participantes' => $puntajes, // Para el overlay del host
                'tendencia' => $tendencia,
                'racha_publico' => $rachaPublico,
                'victoria' => $victoria,
                'golden' => strtolower($data['special_indicator'] ?? '') === 'pregunta de oro',
                // ✅ Agregar información de tendencias del público
                'tendencias_acertadas' => $session->tendencias_acertadas,
                'tendencias_objetivo' => $session->tendencias_objetivo,
                'tendencias_restantes' => $session->tendenciasRestantes(),
                'publico_gano' => $session->publicoGano(),
                // ✅ Contador de preguntas del invitado
                'question_count' => $questionCount,
                'question_limit' => $questionLimit,
                'question_limit_reached' => ($questionCount >= $questionLimit),
            ]));
        } catch (\Throwable $e) {
            \Log::error('❌ Error crítico en broadcast RevealAnswerOverlay', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // No fallar la operación, los datos ya están guardados en BD
        }

        \Log::info('✅ REVEAL: Completado exitosamente', [
            'participantes_notificados' => count($puntajes)
        ]);
        
        return response()->json(['success' => true]);
        
    } catch (\Throwable $e) {
        \Log::error('❌ Error en revealAnswer: '.$e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Ocurrió un error al procesar la petición'], 500);
    }
}

    public function sendRandomQuestion(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id'
        ]);

        // Validar límite de 15 preguntas (igual que en lanzarPreguntaCategoria)
        $session = GameSession::where('status', 'active')->latest()->first();
        if ($session) {
            $questionCount = \App\Models\GuestAnswer::where('game_session_id', $session->id)->count();
            if ($questionCount >= 15) {
                return response()->json([
                    'error' => 'Límite de 15 preguntas alcanzado. El juego del invitado ha terminado.',
                    'limit_reached' => true,
                    'question_count' => $questionCount,
                ], 422);
            }
        }

        $lastQuestionId = session('last_random_question_id');
        $pregunta = Question::where('category_id', $request->categoria_id)
                            ->where('id', '!=', $lastQuestionId)
                            ->inRandomOrder()
                            ->first();

        if(!$pregunta) {
            return response()->json(['error' => 'No hay preguntas disponibles en esta categoría.'], 404);
        }

        $opciones = [
            ['text' => $pregunta->opcion_correcta],
            ['text' => $pregunta->opcion_1],
            ['text' => $pregunta->opcion_2],
            ['text' => $pregunta->opcion_3],
        ];
        shuffle($opciones);

        $data_opciones = [];
        $label_correcto = null;
        foreach ($opciones as $i => $op) {
            $label = chr(65+$i); // A, B, C, D
            $data_opciones[] = [
                'label' => $label,
                'texto' => $op['text'],
            ];
            if ($label_correcto === null && $op['text'] === $pregunta->opcion_correcta) {
                $label_correcto = $label;
            }
        }

        $data = [
            'pregunta' => $pregunta->texto,
            'opciones' => $data_opciones,
            'label_correcto' => $label_correcto,
            'pregunta_id' => $pregunta->id,
            'categoria_id' => $request->categoria_id,
            'timestamp' => now()->toISOString(),
        ];

        // Si hay un indicador especial pendiente (ej.: "PREGUNTA DE ORO", "SOLO YO"),
        // heredarlo para que revealAnswer aplique el scoring correcto y participar bloquee al público.
        $pendingIndicator = session('pending_special_indicator');
        if ($pendingIndicator) {
            $data['special_indicator'] = $pendingIndicator;
            session()->forget('pending_special_indicator');
            \Log::info('🥇 sendRandomQuestion: heredando special_indicator pendiente', [
                'indicator' => $pendingIndicator,
                'pregunta_id' => $pregunta->id,
            ]);
        }

        $pendingDisablePublic = session('pending_disable_public');
        if ($pendingDisablePublic) {
            $data['disable_public_answers'] = true;
            session()->forget('pending_disable_public');
            \Log::info('🔒 sendRandomQuestion: heredando disable_public_answers (Solo Yo)');
        }

        session([
            'last_overlay_question' => $data,
            'last_random_question_id' => $pregunta->id
        ]);

        // Si hay sesión activa, guardar también en pregunta_json para consistencia
        if ($session) {
            $session->pregunta_json = json_encode($data);
            $session->active_question_id = $pregunta->id;
            $session->save();
        }

        broadcast(new NuevaPreguntaOverlay($data));
        return response()->json([
            'success' => true,
            'mensaje' => 'Pregunta enviada',
            'data' => $data
        ]);
    }

public function selectOption(Request $request)
{
    $opcion = $request->input('opcion');

    // Guardar en PHP session (para reveals desde el game panel en la misma sesión)
    session(['selected_guest_option' => $opcion]);

    // Guardar también en Cache (para reveals desde StreamDeck en requests stateless)
    $activeSession = GameSession::where('status', 'active')->latest()->first();
    if ($activeSession) {
        Cache::put('sd_selected_option_' . $activeSession->id, $opcion, now()->addHours(1));
    }

    broadcast(new \App\Events\OpcionSeleccionada($opcion))->toOthers();
    return response()->json(['ok' => true]);
}


public function overlayReset(Request $request)
{
    $session = $this->getActiveSessionCached(5);

    if ($session) {
        if ($session->active_question_id !== null || $session->pregunta_json !== null) {
            // Limpiar flag de tendencia ya contada para la pregunta activa
            if ($session->pregunta_json) {
                $preguntaData = json_decode($session->pregunta_json, true);
                if (!empty($preguntaData['pregunta_id'])) {
                    Cache::forget('tendencia_contada_' . $session->id . '_' . $preguntaData['pregunta_id']);
                }
            }

            $session->active_question_id = null;
            $session->pregunta_json = null;
            $session->save();
            Cache::forget("game_session_active");

            // broadcast solo si hubo write real
            broadcast(new \App\Events\OverlayReset());
        }
    }

    return response()->json(['success' => true]);
}




    public function queueList($sessionId)
    {
        $session = GameSession::findOrFail($sessionId);
        $participants = $session->participants()->orderBy('order')->get();

        if (request()->ajax() || request()->wantsJson()) {
            return view('components.queue-list', compact('participants', 'session'))->render();
        }
        return view('components.queue-list', compact('participants', 'session'));
    }

public function add(Request $request)
{
    $validated = $request->validate([
        'participants.0.username' => 'required|string|max:30',
        'participants.0.dni_last4' => 'required|digits:4',
    ]);

    $session = GameSession::where('status', 'active')->latest()->first();
    if(!$session) {
        return back()->with('error', 'No hay sesión activa.');
    }

    $existingParticipant = ParticipantSession::where('game_session_id', $session->id)
        ->where('username', $validated['participants'][0]['username'])
        ->where('dni_last4', $validated['participants'][0]['dni_last4'])
        ->first();

    if($existingParticipant) {
        // GUARDÁ EL PARTICIPANTE EN SESIÓN Y COOKIE
        session(['participant_session_id' => $existingParticipant->id]);
        Cookie::queue('participant_session_id', $existingParticipant->id, 60*24*30); // 30 días

        // 🔁 Redirección inteligente
        $returnToUrl = session('return_to_url');
        session()->forget('return_to_url');
        if ($returnToUrl) {
            return redirect($returnToUrl)->with('success', 'Ya estás registrado en esta sesión.');
        }

        return back()->with('success', 'Ya estás registrado en esta sesión.');
    }

    // ✅ CREAR PARTICIPANTE CON PROTECCIÓN DE RACE CONDITION EN ORDER
    $participant = DB::transaction(function () use ($validated, $session) {
        // Obtener el orden máximo con lock para evitar duplicados
        $maxOrder = ParticipantSession::where('game_session_id', $session->id)
            ->lockForUpdate()
            ->max('order') ?? 0;

        $participant = new ParticipantSession([
            'username' => $validated['participants'][0]['username'],
            'dni_last4' => $validated['participants'][0]['dni_last4'],
            'game_session_id' => $session->id,
            'status' => 'waiting',
            'order' => $maxOrder + 1,
        ]);
        $participant->save();

        return $participant;
    });

    // GUARDÁ EL NUEVO PARTICIPANTE EN SESIÓN Y COOKIE
    session(['participant_session_id' => $participant->id]);
    Cookie::queue('participant_session_id', $participant->id, 60*24*30); // 30 días

    broadcast(new ParticipantQueueUpdated($session->id));

    // 🔁 Redirección inteligente
    $returnToUrl = session('return_to_url');
    session()->forget('return_to_url');
    if ($returnToUrl) {
        return redirect($returnToUrl)->with('success', '¡Te anotaste en la cola!');
    }
    // 🔁 Redirección por redirect_after_participant_login
    $redirect = session('redirect_after_participant_login');
    if ($redirect) {
        session()->forget('redirect_after_participant_login');
        return redirect()->route($redirect)->with('success', '¡Sesión iniciada! Ya podés jugar.');
    }
    return back()->with('success', '¡Te anotaste en la cola!');
    }

public function ruletaOverlay()
{
    $session = GameSession::where('status', 'active')->latest()->first();
    if (!$session) {
        return back()->with('error', 'No hay sesión activa.');
    }

    $motivo = Motivo::find($session->motivo_id);
    $categorias = $motivo->categorias ?? collect();

    $categories = $categorias->map(function($cat) {
        return [
            'label' => $cat->nombre,
            'color' => $cat->color ?? "#2346c0",
            'textColor' => $cat->text_color ?? "#fff",
            'fixed' => false,
        ];
    })->values()->toArray();

    array_unshift($categories,
        ['label' => "Pregunta de oro", 'color' => "#ffe47a", 'textColor' => "#ad8100", 'fixed' => true],
        ['label' => "Responde el chat", 'color' => "#02204e", 'textColor' => "#00f0ff", 'fixed' => true],
        ['label' => "Solo yo", 'color' => "#101e33", 'textColor' => "#19ff8c", 'fixed' => true],
        ['label' => "Random", 'color' => "#0e223c", 'textColor' => "#ffe47a", 'fixed' => true]
    );

    $sessionGame = ['categories' => $categories];

    // ✅ definirla aquí
    $activeSession = $session;

    return view('overlay', compact('sessionGame', 'activeSession'));
}

public function finalScores()
{
    $session = GameSession::where('status', 'active')->latest()->first();

    if (!$session) {
        // Si no hay sesión activa, buscar la última sesión terminada
        $session = GameSession::where('status', 'ended')->latest()->first();
    }

    if (!$session) {
        return back()->with('error', 'No hay sesión disponible.');
    }

    // Datos del invitado
    $guestName = $session->guest_name ?? 'Invitado';
    $guestScore = $session->guest_points ?? 0;

    // Calcular respuestas correctas e incorrectas del invitado desde guest_answers
    $correctAnswers = \App\Models\GuestAnswer::where('game_session_id', $session->id)
        ->where('is_correct', true)
        ->count();

    $incorrectAnswers = \App\Models\GuestAnswer::where('game_session_id', $session->id)
        ->where('is_correct', false)
        ->count();

    // Obtener todas las preguntas respondidas por el invitado con detalles
    $guestAnswers = \App\Models\GuestAnswer::where('game_session_id', $session->id)
        ->with('question')
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function($answer) {
            $question = $answer->question;

            // Necesitamos reconstruir las opciones como se mostraron originalmente
            // Pero como fueron mezcladas, solo podemos mostrar info genérica
            // Mostraremos todas las opciones disponibles
            $allOptions = [
                $question->opcion_correcta,
                $question->opcion_1,
                $question->opcion_2,
                $question->opcion_3,
            ];

            return [
                'question_text' => $question->texto,
                'all_options' => $allOptions,
                'selected_option' => $answer->selected_option,
                'selected_option_text' => $answer->selected_option_text,
                'correct_option' => $answer->correct_option,
                'is_correct' => $answer->is_correct,
                'correct_text' => $question->opcion_correcta, // Siempre sabemos cuál es la correcta
            ];
        });

    // Top 3 participantes con mayor puntaje
    $topParticipants = ParticipantSession::where('game_session_id', $session->id)
        ->orderBy('puntaje', 'desc')
        ->take(3)
        ->get()
        ->map(function($participant) use ($session) {
            // Calcular respuestas correctas e incorrectas de cada participante
            $correctCount = ParticipantAnswer::where('participant_session_id', $participant->id)
                ->whereRaw('option_label = label_correcto')
                ->count();

            $incorrectCount = ParticipantAnswer::where('participant_session_id', $participant->id)
                ->whereRaw('option_label != label_correcto OR label_correcto IS NULL')
                ->count();

            $participant->correct_answers = $correctCount;
            $participant->incorrect_answers = $incorrectCount;

            return $participant;
        });

    return view('final-scores', compact(
        'guestName',
        'guestScore',
        'correctAnswers',
        'incorrectAnswers',
        'guestAnswers',
        'topParticipants'
    ));
}

public function topParticipants()
{
    $session = GameSession::where('status', 'active')->latest()->first();

    if (!$session) {
        // Si no hay sesión activa, buscar la última sesión terminada
        $session = GameSession::where('status', 'ended')->latest()->first();
    }

    if (!$session) {
        return back()->with('error', 'No hay sesión disponible.');
    }

    // Top 3 participantes con mayor puntaje
    $topParticipants = ParticipantSession::where('game_session_id', $session->id)
        ->orderBy('puntaje', 'desc')
        ->take(3)
        ->get()
        ->map(function($participant) use ($session) {
            // Calcular respuestas correctas e incorrectas de cada participante
            $correctCount = ParticipantAnswer::where('participant_session_id', $participant->id)
                ->whereRaw('option_label = label_correcto')
                ->count();

            $incorrectCount = ParticipantAnswer::where('participant_session_id', $participant->id)
                ->whereRaw('option_label != label_correcto OR label_correcto IS NULL')
                ->count();

            $participant->correct_answers = $correctCount;
            $participant->incorrect_answers = $incorrectCount;

            return $participant;
        });

    return view('top-participants', compact('topParticipants'));
}

public function lanzarPreguntaCategoria(Request $request)
{
    $categoria = $request->input('categoria');
    $categoriaLower = strtolower($categoria);
    $specialSlot = $request->input('special_slot');

    $session = GameSession::where('status', 'active')->latest()->first();
    if (!$session) {
        return response()->json(['error' => 'No hay sesión activa'], 400);
    }

    // ✅ Obtener ID de la pregunta anterior para no repetirla
    $preguntaAnteriorId = $session->active_question_id;

    // Si es random, buscar una categoría random del motivo
    if ($categoriaLower === 'random') {
        $motivo = Motivo::find($session->motivo_id);
        $categorias = $motivo && $motivo->categorias->count() > 0 ? $motivo->categorias : collect();
        if ($categorias->isEmpty()) {
            return response()->json(['error' => 'No hay categorías disponibles para random'], 404);
        }
        $categoriaModel = $categorias->random();
    } 
    // Pregunta de oro
    elseif ($categoriaLower === 'pregunta de oro') {
        // El público no puede contestar → reducir objetivo de tendencias
        $session->reducirObjetivoTendencias();
        \Log::info('🥇 PREGUNTA DE ORO: Reducido objetivo de tendencias', [
            'tendencias_objetivo' => $session->tendencias_objetivo,
            'tendencias_restantes' => $session->tendenciasRestantes()
        ]);

        // Guardar indicador pendiente para que la próxima pregunta que se envíe
        // (vía sendRandomQuestion) lo herede y aplique el scoring de +5/0
        session(['pending_special_indicator' => 'PREGUNTA DE ORO']);

        $data = [
            'pregunta' => strtoupper($categoria),
            'opciones' => [],
            'label_correcto' => null,
            'pregunta_id' => null,
            'categoria_id' => null,
            'categoria_nombre' => 'Pregunta de Oro',
            'timestamp' => now()->toISOString(),
            'special_indicator' => $specialSlot ?? strtoupper($categoria),
            'tendencias_acertadas' => $session->tendencias_acertadas,
            'tendencias_objetivo' => $session->tendencias_objetivo,
            'tendencias_restantes' => $session->tendenciasRestantes(),
        ];
        $session->active_question_id = null;
        $session->pregunta_json = null;
        $session->save();

        session(['last_overlay_question' => $data]);

        broadcast(new NuevaPreguntaOverlay($data));
        return response()->json(['ok' => true]);
    }
    // Chat o Solo Yo
    elseif ($categoriaLower === 'responde el chat' || $categoriaLower === 'solo yo') {
        // ✅ Diferenciar: "Solo yo" deshabilita público, "Responde el chat" no
        $disablePublic = ($categoriaLower === 'solo yo');

        // ✅ Si es "Solo Yo", el público no puede responder, por lo que se reduce el objetivo de tendencias
        if ($categoriaLower === 'solo yo') {
            $session->reducirObjetivoTendencias();
            \Log::info('🔒 SOLO YO: Reducido objetivo de tendencias', [
                'tendencias_objetivo' => $session->tendencias_objetivo,
                'tendencias_restantes' => $session->tendenciasRestantes()
            ]);
            // Guardar pending para que sendRandomQuestion herede el indicador y bloquee al público
            session([
                'pending_special_indicator' => 'SOLO YO',
                'pending_disable_public'    => true,
            ]);
        }

        $data = [
            'pregunta' => strtoupper($categoria),
            'opciones' => [],
            'label_correcto' => null,
            'pregunta_id' => null,
            'categoria_id' => null,
            'categoria_nombre' => ucwords($categoria),
            'timestamp' => now()->toISOString(),
            'special_indicator' => $specialSlot ?? strtoupper($categoria),
            'disable_public_answers' => $disablePublic,
            // Incluir tendencias actualizadas para que el panel se actualice al instante
            'tendencias_acertadas' => $session->tendencias_acertadas,
            'tendencias_objetivo' => $session->tendencias_objetivo,
            'tendencias_restantes' => $session->tendenciasRestantes(),
        ];
        $session->active_question_id = null;
        $session->pregunta_json = null;
        $session->save();

        session(['last_overlay_question' => $data]);

        broadcast(new NuevaPreguntaOverlay($data));
        return response()->json(['ok' => true]);
    }
    // Si es categoría normal
    else {
        $categoriaModel = Categoria::where('nombre', $categoria)->first();
        if (!$categoriaModel) {
            return response()->json(['error' => 'Categoría no encontrada: '.$categoria], 404);
        }
    }

    // ✅ VERIFICAR LÍMITE DE 15 PREGUNTAS DEL INVITADO
    $questionCount = \App\Models\GuestAnswer::where('game_session_id', $session->id)->count();
    if ($questionCount >= 15) {
        return response()->json([
            'error' => 'Límite de 15 preguntas alcanzado. El juego del invitado ha terminado.',
            'limit_reached' => true,
            'question_count' => $questionCount,
        ], 422);
    }

    // ✅ BUSCAR PREGUNTA ALEATORIA EXCLUYENDO LA ANTERIOR
    $query = Question::where('category_id', $categoriaModel->id);
    
    // Excluir la pregunta anterior si existe
    if ($preguntaAnteriorId) {
        $query->where('id', '!=', $preguntaAnteriorId);
    }
    
    $pregunta = $query->inRandomOrder()->first();
    
    // Si no hay más preguntas (solo había 1 y era la anterior), permitir repetirla
    if (!$pregunta) {
        \Log::warning('⚠️ No hay más preguntas disponibles, permitiendo repetir');
        $pregunta = Question::where('category_id', $categoriaModel->id)->inRandomOrder()->first();
    }
    
    if (!$pregunta) {
        return response()->json(['error' => 'No hay preguntas disponibles en esta categoría: '.$categoriaModel->nombre], 404);
    }

    // Armar opciones y mezclar solo una vez
    $opciones = [
        ['text' => $pregunta->opcion_correcta],
        ['text' => $pregunta->opcion_1],
        ['text' => $pregunta->opcion_2],
        ['text' => $pregunta->opcion_3],
    ];
    shuffle($opciones);

    $data_opciones = [];
    $label_correcto = null;
    foreach ($opciones as $i => $op) {
        $label = chr(65 + $i);
        $data_opciones[] = [
            'label' => $label,
            'texto' => $op['text'],
        ];
        if ($label_correcto === null && $op['text'] === $pregunta->opcion_correcta) {
            $label_correcto = $label;
        }
    }

    $data = [
        'pregunta' => $pregunta->texto,
        'opciones' => $data_opciones,
        'label_correcto' => $label_correcto,
        'pregunta_id' => $pregunta->id,
        'categoria_id' => $pregunta->category_id,
        'categoria_nombre' => $categoriaModel->nombre, // ✅ AGREGADO - Este es el más importante
        'timestamp' => now()->toISOString(),
    ];

    if ($specialSlot) {
        $data['special_indicator'] = $specialSlot;
    }

    // Guardar la pregunta en la sesión activa (BD)
    $session->active_question_id = $pregunta->id;
    $session->pregunta_json = json_encode($data);
    $session->save();
    
    session(['last_overlay_question' => $data]);
    
    \Log::info('🟢 PREGUNTA GUARDADA', [
        'pregunta_id' => $pregunta->id, 
        'label_correcto' => $label_correcto,
        'anterior_id' => $preguntaAnteriorId,
        'categoria' => $categoriaModel->nombre // ✅ AGREGADO al log
    ]);

    broadcast(new NuevaPreguntaOverlay($data));
    return response()->json(['success' => true, 'data' => $data]);
}

    public function girarRuleta() {
        broadcast(new \App\Events\GirarRuleta());
        return response()->json(['ok' => true]);
    }

    public function syncQuestion(Request $request) {
        $data = $request->input('pregunta');
        if ($data) {
            session(['last_overlay_question' => $data]);
            return response()->json(['ok' => true]);
        }
        return response()->json(['ok' => false], 400);
    }

public function participar(Request $request)
{
    $participantSessionId = session('participant_session_id');
    $participant = $participantSessionId
        ? ParticipantSession::find($participantSessionId)
        : null;

    if (!$participant) {
        session(['return_to_url' => url()->full()]);
        return redirect()->route('participants.form');
    }

    $session = GameSession::where('status', 'active')->latest()->first();

    // 🚩 JAMÁS volver a mezclar ni armar opciones acá. Solo leer lo que está en la BD.
    $data = null;
    if ($session && $session->pregunta_json) {
        $data = json_decode($session->pregunta_json, true);
    }

    // Buscar si ya respondió a la pregunta actual
    $yaRespondio = null;
    if ($participant && isset($data['pregunta_id'])) {
        $yaRespondio = \App\Models\ParticipantAnswer::where('participant_session_id', $participant->id)
            ->where('question_id', $data['pregunta_id'])
            ->first();
    }

    // SIEMPRE calcular puntaje, y si es null, poner en 0
// obtener session/servicio
$session = GameSession::where('status', 'active')->latest()->first();
$gamePoints = app(\App\Services\GamePointsService::class);

if ($participant && $session) {
    // recalculamos puntajes usando el método de lote que ya existe en el service
    $puntajesMap = $gamePoints->calcularPuntajesParticipantes([$participant->id], $session->id);
    // $puntajesMap debería ser algo como [participant_id => ['total'=>X,'detalles'=>...]]
    $puntaje = $puntajesMap[$participant->id] ?? ['total' => 0, 'detalles' => []];
} else {
    $puntaje = ['total' => 0, 'detalles' => []];
}

    // SIEMPRE pasar participant y puntaje a la vista
    return view('participar', [
        'pregunta' => $data,
        'yaRespondio' => $yaRespondio ? $yaRespondio->option_label : null,
        'puntaje' => $puntaje,
        'participant' => $participant,
        'sinPregunta' => !$data,
    ]);
}
public function enviarParticipacion(Request $request)
{
    $request->validate([
        'option_label' => 'required|in:A,B,C,D',
        'question_id' => 'required|exists:questions,id'
    ]);

    // 1. Obtener participante desde la session
    $participantSessionId = session('participant_session_id');
    $participant = $participantSessionId ? ParticipantSession::find($participantSessionId) : null;
    if (!$participant) {
        return redirect()->route('participants.form')->with('error', 'Debes iniciar sesión como participante primero.');
    }

    // 2. Obtener label_correcto de la pregunta_json de la sesión activa
    $labelCorrecto = null;
    $session = GameSession::where('status', 'active')->latest()->first();

    if ($session && $session->pregunta_json) {
        $data = json_decode($session->pregunta_json, true);

        if (isset($data['pregunta_id']) && $data['pregunta_id'] == $request->question_id) {
            $labelCorrecto = $data['label_correcto'] ?? null;
        }
    }

    // 3. Fallback (nunca guardar null)
    if (!$labelCorrecto) {
        $prevAnswer = ParticipantAnswer::where('question_id', $request->question_id)
            ->whereNotNull('label_correcto')
            ->latest('id')
            ->first();
        if ($prevAnswer) {
            $labelCorrecto = $prevAnswer->label_correcto;
        }
    }

    // 4. Fallback final
    if (!$labelCorrecto) {
        $question = \App\Models\Question::find($request->question_id);
        if ($question && method_exists($question, 'getCorrectLabel')) {
            $labelCorrecto = $question->getCorrectLabel();
        }
    }

    // 5. Log para depuración
    \Log::info("GUARDAR RESPUESTA: qid={$request->question_id}, label_correcto={$labelCorrecto}, seleccionada={$request->option_label}");

    // 6. Guardar la respuesta CON PROTECCIÓN DE RACE CONDITION
    DB::transaction(function () use ($participant, $request, $labelCorrecto) {
        ParticipantAnswer::updateOrCreate(
            [
                'participant_session_id' => $participant->id,
                'question_id' => $request->question_id,
            ],
            [
                'option_label' => $request->option_label,
                'label_correcto' => $labelCorrecto,
            ]
        );
    });

    // 7. Tendencia y votos (calculado después de guardar)
    $questionId = $request->question_id;
    $votedOption = $request->option_label;

    // Usar shared lock para lectura consistente
    $votes = DB::transaction(function () use ($questionId) {
        return \App\Models\ParticipantAnswer::where('question_id', $questionId)
            ->sharedLock()
            ->select('option_label', DB::raw('count(*) as total'))
            ->groupBy('option_label')
            ->get();
    });

    if ($votes->count() === 1) {
        $trendOption = $votedOption;
        $trendTotal = 1;
    } else {
        $max = $votes->max('total');
        $candidates = $votes->where('total', $max)->pluck('option_label')->toArray();

        if (in_array($votedOption, $candidates)) {
            $trendOption = $votedOption;
        } else {
            $trendOption = $candidates[0];
        }
        $trendTotal = $max;
    }

    if (isset($trendOption)) {
        broadcast(new \App\Events\TendenciaActualizada([
            'question_id' => $questionId,
            'option_label' => $trendOption,
            'total' => $trendTotal,
        ]));
    }

    return redirect()->back()->with('success', '¡Respuesta enviada!');
}


public function apiActiveQuestion()
{
    // 🔥 Aumentar TTL y evitar writes innecesarios
    $session = Cache::remember("game_session_active_question", 8, function () {
        $session = GameSession::where('status', 'active')->latest()->first();
        return $session ? [
            'pregunta_json' => $session->pregunta_json,
            'id' => $session->id
        ] : null;
    });

    if (!$session || !$session['pregunta_json']) {
        return response()->json(['pregunta' => null]);
    }
    
    return response()->json(json_decode($session['pregunta_json'], true));
}

public function limpiarPreguntaParticipante()
{
    session()->forget('last_overlay_question');
    return response()->json(['ok' => true]);
}
// Agregar este método al GameSessionController

public function resetParticipante(Request $request)
{
    $request->validate([
        'question_id' => 'required|exists:questions,id'
    ]);

    $participantSessionId = session('participant_session_id');
    $participant = $participantSessionId ? ParticipantSession::find($participantSessionId) : null;
    
    if (!$participant) {
        return response()->json(['error' => 'Participante no encontrado'], 404);
    }

    // Eliminar la respuesta anterior si existe
    ParticipantAnswer::where('participant_session_id', $participant->id)
        ->where('question_id', $request->question_id)
        ->delete();

    return response()->json(['success' => true]);
}
public function showParticipantForm(Request $request)
{
    // Guarda el redirect sólo si viene en la URL
    if ($request->has('redirect')) {
        session(['redirect_after_participant_login' => $request->input('redirect')]);
    }
    return view('auth.participants-form');
}
public function salirDelJuego(Request $request)
{
    // 1. Obtener el ID del participante desde la sesión (antes de forget)
    $participantSessionId = session('participant_session_id');

    // 2. Borrar la sesión y la cookie
    session()->forget('participant_session_id');
    \Cookie::queue(\Cookie::forget('participant_session_id'));

    // 3. Si existe, eliminar el registro del participante (y opcional: sus respuestas)
    if ($participantSessionId) {
        // Guardar el game_session_id antes de eliminarlo
        $participant = \App\Models\ParticipantSession::find($participantSessionId);
        $sessionId = $participant ? $participant->game_session_id : null;

        // Borra sus respuestas primero, si corresponde
        \App\Models\ParticipantAnswer::where('participant_session_id', $participantSessionId)->delete();
        // Borra el participante de la cola
        \App\Models\ParticipantSession::where('id', $participantSessionId)->delete();

        // **LANZA EL EVENTO AQUÍ**
        if ($sessionId) {
            broadcast(new \App\Events\ParticipantQueueUpdated($sessionId));
        }
    }

    // 4. Redirigir
    return redirect()->route('guest-dashboard')->with('success', 'Saliste del juego y tu registro fue eliminado.');
}
// En GameSessionController:
public function apiGuestPoints()
{
    $points = Cache::remember("guest_points_overlay", 3, function () {
        $session = GameSession::where('status', 'active')->latest()->first();
        return $session ? $session->guest_points ?? 0 : 0;
    });
    
    return response()->json(['points' => $points]);
}
/* public function activarApuestaX2($id)
{
    $session = GameSession::findOrFail($id);

    if ($session->apuesta_x2_active) {
        return response()->json(['ok' => false, 'msg' => 'Ya tienes una apuesta x2 activa']);
    }

    $session->apuesta_x2_active = true;
    $session->save();

    session(['guest_apuesta_x2' => true]);

    return response()->json(['ok' => true, 'msg' => 'Apuesta x2 activada']);
}

public function desactivarApuestaX2($id)
{
    $session = GameSession::findOrFail($id);

    $session->apuesta_x2_active = false;
    $session->save();

    session()->forget('guest_apuesta_x2');

    return response()->json(['ok' => true, 'msg' => 'Apuesta x2 desactivada']);
}

public function usarDescarte($id)
{
    $session = GameSession::findOrFail($id);

    if ($session->descarte_usados >= 3) { // por ejemplo, máximo 3 descartes
        return response()->json(['ok' => false, 'msg' => 'Ya usaste todos los descartes disponibles']);
    }

    $session->descarte_usados++;
    $session->save();

    return response()->json(['ok' => true, 'msg' => 'Descarte usado', 'total_usados' => $session->descarte_usados]);
}

public function resetearDescarte($id)
{
    $session = GameSession::findOrFail($id);

    $session->descarte_usados = 0;
    $session->save();

    return response()->json(['ok' => true, 'msg' => 'Descartes reseteados']);
}
*/

}
