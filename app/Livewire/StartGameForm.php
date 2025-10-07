<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\GameSession;

class StartGameForm extends Component
{
    public $motivos;
    public $selectedMotivo;

    public function startGame()
    {
        GameSession::create([
            'motivo_id' => $this->selectedMotivo,
            'status' => 'active'
        ]);

        $this->emit('gameStarted');
    }

    public function render()
    {
        return view('livewire.start-game-form');
    }
}
