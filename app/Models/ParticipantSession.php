<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

/**
 * Los participantes tienen su propio sistema de tokens (Sanctum), separado
 * del guard de session/cookie que usa la web y del modelo User (hosts/admins).
 * Esto permite que la app mobile y la web jueguen en la misma game_session
 * en simultáneo: ambas leen/escriben las mismas filas de esta tabla.
 */
class ParticipantSession extends Model implements AuthenticatableContract
{
    use HasFactory, HasApiTokens, Authenticatable;

    protected $fillable = [
        'game_session_id',
        'username',
        'dni_last4',
        'order',
        'status',
        'puntaje',
    ];

    // Relación con la sesión de juego
    public function gameSession()
    {
        return $this->belongsTo(GameSession::class);
    }
}

