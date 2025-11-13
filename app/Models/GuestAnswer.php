<?php
// app/Models/GuestAnswer.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestAnswer extends Model
{
    protected $fillable = [
        'game_session_id',
        'question_id',
        'selected_option',
        'selected_option_text',
        'correct_option',
        'is_correct',
        'points_awarded',
        'apuesta_x2',
        'was_discarded'
    ];

    public function gameSession()
    {
        return $this->belongsTo(GameSession::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
