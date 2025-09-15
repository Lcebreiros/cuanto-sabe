<?php

// app/Models/GameSession.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSession extends Model
{
    protected $fillable = ['guest_name', 'motivo_id', 'status', 'guest_points'];

    public function motivo()
    {
        return $this->belongsTo(Motivo::class);
    }

    public function participants()
{
    return $this->hasMany(\App\Models\ParticipantSession::class);
}

}
