<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\AdminMiddleware as IsAdmin;
use App\Models\Question;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\QuestionImportController;
use App\Http\Controllers\GameBonusController;
use App\Http\Controllers\StreamDeckController;
use App\Livewire\TeamAdmin;

// Ruta raíz
Route::get('/', fn() => view('welcome'))->name('home');

// Dashboard para invitados y usuarios autenticados
Route::get('/guest-dashboard', fn() => view('guest-dashboard'))->name('guest-dashboard');
Route::get('/dashboard', fn() => view('dashboard'))->middleware(['auth', 'verified', 'admin'])->name('dashboard');

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Panel admin
//Route::middleware(['auth', IsAdmin::class])->get('/admin', fn() => view('admin'))->name('admin');

// Preguntas (solo admin)
Route::get('/questions', [QuestionController::class, 'index'])->middleware(['auth', 'admin'])->name('questions');

// Rutas de administración de usuarios (solo admin)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.updateRole');
    Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/panel', fn() => view('users'))->name('panel');
        // Aquí podés agregar más rutas admin si necesitas
    });
});

// Juego (panel y alta)
Route::get('/juego', [GameController::class, 'controlPanel'])
    ->name('juego.panel')
    ->middleware(['auth', 'admin']);
Route::post('/motivo', [GameController::class, 'storeMotivo'])->name('motivo.store');
Route::post('/categoria', [GameController::class, 'storeCategoria'])->name('categoria.store');
Route::post('/pregunta', [GameController::class, 'storePregunta'])->name('pregunta.store');

// Sesiones de juego (crear/finalizar)
Route::post('/game-session/start', [GameSessionController::class, 'start'])->name('game-session.start');
Route::post('/game-session/end', [GameSessionController::class, 'end'])->name('game-session.end');

// Overlay de juego para invitados (puede ser el panel público)
Route::get('/jugar', fn() => view('game.participate'))->name('game.participate');

Route::get('/overlay', [App\Http\Controllers\GameSessionController::class, 'ruletaOverlay'])->name('overlay');
Route::get('/final-scores', [App\Http\Controllers\GameSessionController::class, 'finalScores'])->name('final-scores');
Route::get('/top-participants', [App\Http\Controllers\GameSessionController::class, 'topParticipants'])->name('top-participants');


// Acciones del overlay/sesión de juego
Route::post('/game-session/reveal', [GameSessionController::class, 'revealAnswer'])->name('game-session.reveal');
Route::post('/game-session/random-question', [GameSessionController::class, 'sendRandomQuestion'])->name('game-session.random-question');
Route::post('/game-session/overlay-reset', [GameSessionController::class, 'overlayReset'])->name('game-session.overlay-reset');
Route::post('/game-session/select-option', [GameSessionController::class, 'selectOption'])->name('game-session.select-option');
Route::get('/overlay/api/puntos', [GameSessionController::class, 'apiGuestPoints']);
Route::get('/overlay/api/pregunta', [GameSessionController::class, 'apiActiveQuestion']);

// bonos y descartes
Route::prefix('game')->group(function () {
    Route::post('/apuesta-x2/toggle', [GameBonusController::class, 'toggleApuestaX2'])->name('game.toggleApuestaX2');
    Route::post('/descarte/toggle', [GameBonusController::class, 'toggleDescarte'])->name('game.toggleDescarte');
});


// Participantes
Route::get('/participants/form', [GameSessionController::class, 'showParticipantForm'])->name('participants.form');
Route::post('/participants/add', [GameSessionController::class, 'add'])->name('participants.add');
Route::get('/participants/list', [GameSessionController::class, 'showParticipants'])->name('participants.list');
Route::post('/salir-juego', [App\Http\Controllers\GameSessionController::class, 'salirDelJuego'])->name('salir.juego');

// Cola de participantes (queue)
Route::get('/queue-list/{session}', [GameSessionController::class, 'queueList'])->name('queue-list');
Route::get('/game-sessions/{sessionId}/queue-list', [GameSessionController::class, 'queueList'])->name('game-sessions.queue-list');

// RULETA OVERLAY y POST para lanzar pregunta
Route::get('/ruleta', [GameSessionController::class, 'ruletaOverlay'])->name('ruleta');
Route::post('/overlay/lanzar-pregunta', [GameSessionController::class, 'lanzarPreguntaCategoria'])
    ->name('overlay.lanzar-pregunta');

Route::post('/game-session/girar-ruleta', [GameSessionController::class, 'girarRuleta'])->name('game-session.girar-ruleta');
Route::post('/game-session/limpiar-spin-pendiente', [GameSessionController::class, 'limpiarSpinPendiente'])->name('game-session.limpiar-spin-pendiente');
Route::post('/game-session/lanzar-pregunta-finalizar', [GameSessionController::class, 'lanzarPreguntaAlFinalizar'])->name('game-session.lanzar-pregunta-finalizar');

Route::post('/game-session/sync-question', [GameSessionController::class, 'syncQuestion'])->name('game-session.sync-question');

Route::get('/participar', [GameSessionController::class, 'participar'])->name('participar');
Route::post('/participar/enviar', [GameSessionController::class, 'enviarParticipacion'])->name('participar.enviar');
// En routes/web.php
Route::get('/api/active-question', [GameSessionController::class, 'apiActiveQuestion']);
Route::post('/participar/limpiar', [App\Http\Controllers\GameSessionController::class, 'limpiarPreguntaParticipante'])->name('participar.limpiar');
Route::post('/participar/reset', [GameSessionController::class, 'resetParticipante'])->name('participar.reset');
Route::post('/salir', [GameSessionController::class, 'salirDelJuego'])->name('salirDelJuego');

// Chat (solo admin)
Route::get('/chat', fn() => view('chat'))->middleware(['auth', 'admin'])->name('chat');

// ─── Stream Deck API (sin CSRF, autenticada con STREAMDECK_TOKEN) ───────────
// Usada por Bitfocus Companion para controlar el juego desde el hardware Stream Deck.
// Companion pollea GET /sd/state?token=TOKEN cada ~1s y dispara POST /sd/{accion}
Route::middleware(['streamdeck.auth'])->prefix('sd')->group(function () {
    Route::get('/state',     [StreamDeckController::class, 'state']);     // Estado completo (para polling)
    Route::post('/ruleta',   [StreamDeckController::class, 'ruleta']);    // Toggle girar/parar ruleta
    Route::post('/revelar',  [StreamDeckController::class, 'revelar']);   // Revelar respuesta
    Route::post('/refrescar',[StreamDeckController::class, 'refrescar']); // Reset overlay
    Route::post('/apuesta',       [StreamDeckController::class, 'apuesta']);        // Toggle apuesta x2
    Route::post('/descarte',      [StreamDeckController::class, 'descarte']);       // Usar descarte
    Route::post('/opcion/{label}',[StreamDeckController::class, 'opcion']);         // Seleccionar opción A/B/C/D
});

// Stream Deck UI (solo admin)
Route::get('/streamdeck', function () {
    $activeSession = \App\Models\GameSession::where('status', 'active')->latest()->first();
    $questionCount = $activeSession
        ? \App\Models\GuestAnswer::where('game_session_id', $activeSession->id)->count()
        : 0;
    return view('streamdeck', compact('activeSession', 'questionCount'));
})->middleware(['auth', 'admin'])->name('streamdeck');

// Demo
// routes/web.php
Route::view('/demo', 'demo')->name('demo');

Route::get('/questions/import', [QuestionImportController::class, 'create'])->name('questions.import.create');
Route::post('/questions/import', [QuestionImportController::class, 'store'])->name('questions.import.store');

// vista conocenos

Route::get('/about', function () {
    return view('about-us');
})->name('about-us');

Route::middleware(['auth', 'can:edit pages'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('team', \App\Http\Controllers\Admin\TeamController::class);
});

// vista team
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/team', 'admin.team')->name('team');
});

// reglas
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/rules', 'admin.rules')->name('rules');
});



require __DIR__.'/auth.php';
