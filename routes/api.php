<?php

use App\Http\Controllers\Api\ParticipantAuthController;
use App\Http\Controllers\Api\ParticipateController;
use Illuminate\Support\Facades\Route;

// ─── Auth de participantes (app mobile) — token Sanctum propio de ParticipantSession,
// independiente del guard de sesión/cookie de la web y del login de host/admin ────────
Route::post('/participants/join', [ParticipantAuthController::class, 'join'])
    ->middleware('throttle:15,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/participants/logout', [ParticipantAuthController::class, 'logout']);
    Route::post('/participants/leave', [ParticipantAuthController::class, 'leave']);

    // ─── Flujo de juego del participante, equivalente JSON de /participar en la web ───
    Route::get('/participate/state', [ParticipateController::class, 'state']);
    Route::post('/participate/answer', [ParticipateController::class, 'answer'])
        ->middleware('throttle:60,1');
    Route::delete('/participate/answer', [ParticipateController::class, 'resetAnswer']);
    Route::get('/participate/result', [ParticipateController::class, 'result']);
});
