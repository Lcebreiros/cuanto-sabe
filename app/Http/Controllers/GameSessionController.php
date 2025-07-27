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


class GameSessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'guest_name' => 'required|string|max:50',
            'motivo_id' => 'required|exists:motivos,id',
        ]);

        GameSession::where('status', 'active')->update(['status' => 'ended']);

        $session = GameSession::create([
            'guest_name' => $request->guest_name,
            'motivo_id' => $request->motivo_id,
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', '¡Juego iniciado para ' . $session->guest_name . '!');
    }

    public function end()
    {
        GameSession::where('status', 'active')->update(['status' => 'ended']);
        return redirect()->back()->with('success', 'Juego finalizado.');
    }

    public function revealAnswer(Request $request)
    {
        // Usa el array de la última pregunta lanzada (mezclado)
        $data = session('last_overlay_question', null);
        if (!$data) {
            return response()->json(['error' => 'No hay pregunta activa en sesión'], 400);
        }
        broadcast(new \App\Events\RevealAnswerOverlay($data));
        return response()->json(['success' => true]);
    }

    public function sendRandomQuestion(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id'
        ]);

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

        session([
            'last_overlay_question' => $data, // 🟢 CORREGIDO
            'last_random_question_id' => $pregunta->id
        ]);

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
        broadcast(new \App\Events\OpcionSeleccionada($opcion))->toOthers();
        return response()->json(['ok' => true]);
    }

public function overlayReset(Request $request)
{
    // Buscar la sesión de juego activa
    $session = \App\Models\GameSession::where('status', 'active')->latest()->first();
    if ($session) {
        $session->active_question_id = null;
        $session->save();
    }
    broadcast(new \App\Events\OverlayReset());
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

    $participant = new ParticipantSession([
        'username' => $validated['participants'][0]['username'],
        'dni_last4' => $validated['participants'][0]['dni_last4'],
        'game_session_id' => $session->id,
        'status' => 'waiting',
        'order' => $session->participants()->max('order') + 1,
    ]);
    $participant->save();

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

        return view('overlay', compact('sessionGame'));
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

    if ($categoriaLower === 'random') {
        $motivo = Motivo::find($session->motivo_id);
        $categorias = $motivo ? $motivo->categorias : collect();
        $categoriaModel = $categorias->random();
    } elseif ($categoriaLower === 'pregunta de oro') {
        // Lógica especial para pregunta de oro si la tenés...
        // Por ahora solo ejemplo
        $data = [
            'pregunta' => strtoupper($categoria),
            'opciones' => [],
            'label_correcto' => null,
            'pregunta_id' => null,
            'categoria_id' => null,
            'timestamp' => now()->toISOString(),
            'special_indicator' => $specialSlot ?? strtoupper($categoria),
        ];
        // Limpiar pregunta activa en BD
        $session->active_question_id = null;
        $session->save();
        broadcast(new NuevaPreguntaOverlay($data));
        return response()->json(['ok' => true]);
    } elseif ($categoriaLower === 'responde el chat' || $categoriaLower === 'solo yo') {
        $data = [
            'pregunta' => strtoupper($categoria),
            'opciones' => [],
            'label_correcto' => null,
            'pregunta_id' => null,
            'categoria_id' => null,
            'timestamp' => now()->toISOString(),
            'special_indicator' => $specialSlot ?? strtoupper($categoria),
        ];
        // Limpiar pregunta activa en BD
        $session->active_question_id = null;
        $session->save();
        broadcast(new NuevaPreguntaOverlay($data));
        return response()->json(['ok' => true]);
    } else {
        $categoriaModel = Categoria::where('nombre', $categoria)->first();
        if (!$categoriaModel) {
            return response()->json(['error' => 'Categoría no encontrada'], 404);
        }
    }

    $pregunta = Question::where('category_id', $categoriaModel->id)->inRandomOrder()->first();
    if(!$pregunta) return response()->json(['error' => 'No hay preguntas disponibles en esta categoría.'], 404);

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
        'timestamp' => now()->toISOString(),
    ];

    if ($specialSlot) {
        $data['special_indicator'] = $specialSlot;
    }

    // 👉 Guardar la pregunta activa SOLO en la sesión activa:
    $session->active_question_id = $pregunta->id;
    $session->save();

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

    $data = null;
    if ($session && $session->active_question_id) {
        $pregunta = Question::find($session->active_question_id);
        if ($pregunta) {
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
                'timestamp' => now()->toISOString(),
            ];
        }
    }
if (!$data) {
    // 🔥 Limpiá la pregunta anterior de la sesión, así el blade nunca la muestra
    session()->forget('last_overlay_question');
    return view('participar', ['sinPregunta' => true]);
}


    // Buscar si ya respondió a la pregunta actual
    $yaRespondio = null;
    if ($participant && isset($data['pregunta_id'])) {
        $yaRespondio = \App\Models\ParticipantAnswer::where('participant_session_id', $participant->id)
            ->where('question_id', $data['pregunta_id'])
            ->first();
    }

    return view('participar', [
        'pregunta' => $data,
        'yaRespondio' => $yaRespondio ? $yaRespondio->option_label : null,
    ]);
}


public function enviarParticipacion(Request $request)
{
    $request->validate([
        'option_label' => 'required|in:A,B,C,D',
        'question_id' => 'required|exists:questions,id'
    ]);

    // ⚡️ Obtener el participante desde la session
    $participantSessionId = session('participant_session_id');
    $participant = $participantSessionId ? ParticipantSession::find($participantSessionId) : null;
    if (!$participant) {
        return redirect()->route('participants.form')->with('error', 'Debes iniciar sesión como participante primero.');
    }

    // Guardar o actualizar la respuesta
    ParticipantAnswer::updateOrCreate(
        [
            'participant_session_id' => $participant->id,
            'question_id' => $request->question_id,
        ],
        [
            'option_label' => $request->option_label,
        ]
    );

$questionId = $request->question_id;
$votedOption = $request->option_label;

// Obtener el recuento de votos por opción
$votes = \App\Models\ParticipantAnswer::where('question_id', $questionId)
    ->select('option_label', DB::raw('count(*) as total'))
    ->groupBy('option_label')
    ->get();

if ($votes->count() === 1) {
    // Primer voto, tendencia es la opción votada
    $trendOption = $votedOption;
    $trendTotal = 1;
} else {
    // Busca el máximo de votos
    $max = $votes->max('total');
    // Opciones empatadas con ese máximo
    $candidates = $votes->where('total', $max)->pluck('option_label')->toArray();

    if (in_array($votedOption, $candidates)) {
        // Si la opción recién votada está entre las empatadas, la tendencia es esa
        $trendOption = $votedOption;
    } else {
        // Si no, toma la primera del array (no debería pasar casi nunca)
        $trendOption = $candidates[0];
    }
    $trendTotal = $max;
}

if ($trendOption) {
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
    $data = session('last_overlay_question', null);
    if (!$data) {
        return response()->json(['pregunta' => null]);
    }
    return response()->json($data);
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


}
