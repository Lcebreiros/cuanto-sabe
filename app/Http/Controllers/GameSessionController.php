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
            return back()->with('error', 'Ya estás registrado en esta sesión.');
        }

        $participant = new ParticipantSession([
            'username' => $validated['participants'][0]['username'],
            'dni_last4' => $validated['participants'][0]['dni_last4'],
            'game_session_id' => $session->id,
            'status' => 'waiting',
            'order' => $session->participants()->max('order') + 1,
        ]);
        $participant->save();

        broadcast(new ParticipantQueueUpdated($session->id));
        return redirect()->back()->with('success', '¡Te anotaste en la cola!');
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

        if ($categoriaLower === 'random') {
            $session = GameSession::where('status', 'active')->latest()->first();
            $motivo = $session ? Motivo::find($session->motivo_id) : null;
            $categorias = $motivo ? $motivo->categorias : collect();
            $categoriaModel = $categorias->random();
        } elseif ($categoriaLower === 'pregunta de oro') {
            // Tu lógica especial si aplica...
        } elseif ($categoriaLower === 'responde el chat' || $categoriaLower === 'solo yo') {
            $data = [
                'pregunta' => strtoupper($categoria),
                'opciones' => [],
                'label_correcto' => null,
                'pregunta_id' => null,
                'categoria_id' => null,
                'timestamp' => now()->toISOString(),
            ];
            session(['last_overlay_question' => $data]);
            broadcast(new NuevaPreguntaOverlay($data));
            return response()->json(['ok' => true]);
        } else {
            $categoriaModel = Categoria::where('nombre', $categoria)->first();
            if (!$categoriaModel) return response()->json(['error' => 'Categoría no encontrada'], 404);
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

        // 🟢 GUARDA EN SESSION EL ARRAY DE OPCIONES MEZCLADAS (para el reveal)
        session(['last_overlay_question' => $data]);

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

}
