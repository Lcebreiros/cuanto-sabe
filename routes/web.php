<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\IsAdmin;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;

// Ruta raíz
Route::get('/', fn() => view('welcome'))->name('home');

// Dashboard para invitados y usuarios autenticados
Route::get('/guest.dashboard', fn() => view('guest-dashboard'))->name('guest-dashboard');
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel admin
Route::middleware(['auth', IsAdmin::class])->get('/admin', fn() => view('admin'))->name('admin');

// Preguntas (público)
Route::get('/questions', [QuestionController::class, 'index'])->name('questions');

// Rutas de administración de usuarios (solo admin)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/panel', fn() => view('admin'))->name('panel');
        // Aquí podés agregar más rutas admin si necesitas
    });
});

// Juego (panel y alta)
Route::get('/juego', [GameController::class, 'controlPanel'])->name('juego.panel');
Route::post('/motivo', [GameController::class, 'storeMotivo'])->name('motivo.store');
Route::post('/categoria', [GameController::class, 'storeCategoria'])->name('categoria.store');
Route::post('/pregunta', [GameController::class, 'storePregunta'])->name('pregunta.store');

// Sesiones de juego (crear/finalizar)
Route::post('/game-session/start', [GameSessionController::class, 'start'])->name('game-session.start');
Route::post('/game-session/end', [GameSessionController::class, 'end'])->name('game-session.end');

// Overlay de juego para invitados (puede ser el panel público)
Route::get('/jugar', fn() => view('game.participate'))->name('game.participate');

// Overlay clásico (opcional, si lo usás)
use App\Models\Question;
// Route::get('/overlay', function() {
//     $preguntaActual = Question::where('is_active', true)->first();
//     return view('overlay', [
//         'pregunta' => $preguntaActual?->texto ?? 'Esperando pregunta...',
//         'opciones' => [
//             'A' => $preguntaActual?->opcion_correcta ?? 'Opción A',
//             'B' => $preguntaActual?->opcion_1 ?? 'Opción B',
//             'C' => $preguntaActual?->opcion_2 ?? 'Opción C',
//             'D' => $preguntaActual?->opcion_3 ?? 'Opción D',
//         ],
//         'votos' => ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0],
//         'record' => 0,
//     ]);
// });

Route::get('/overlay', [App\Http\Controllers\GameSessionController::class, 'ruletaOverlay'])->name('overlay');


// Acciones del overlay/sesión de juego
Route::post('/game-session/reveal', [GameSessionController::class, 'revealAnswer'])->name('game-session.reveal');
Route::post('/game-session/random-question', [GameSessionController::class, 'sendRandomQuestion'])->name('game-session.random-question');
Route::post('/game-session/overlay-reset', [GameSessionController::class, 'overlayReset'])->name('game-session.overlay-reset');
Route::post('/game-session/select-option', [GameSessionController::class, 'selectOption'])->name('game-session.select-option');

// Participantes
Route::get('/participants/form', [GameSessionController::class, 'showParticipantForm'])->name('participants.form');
Route::post('/participants/add', [GameSessionController::class, 'add'])->name('participants.add');
Route::get('/participants/list', [GameSessionController::class, 'showParticipants'])->name('participants.list');
Route::get('/participar', fn() => view('auth.participants-form'))->name('participants.form');

// Cola de participantes (queue)
Route::get('/queue-list/{session}', [GameSessionController::class, 'queueList'])->name('queue-list');
Route::get('/game-sessions/{sessionId}/queue-list', [GameSessionController::class, 'queueList'])->name('game-sessions.queue-list');

// RULETA OVERLAY y POST para lanzar pregunta
Route::get('/ruleta', [GameSessionController::class, 'ruletaOverlay'])->name('ruleta');
Route::post('/overlay/lanzar-pregunta', [GameSessionController::class, 'lanzarPreguntaCategoria']);

Route::get('/overlay', [GameSessionController::class, 'ruletaOverlay']);

Route::post('/game-session/girar-ruleta', [GameSessionController::class, 'girarRuleta']);

Route::post('/game-session/sync-question', [GameSessionController::class, 'syncQuestion'])->name('game-session.sync-question');


require __DIR__.'/auth.php';
