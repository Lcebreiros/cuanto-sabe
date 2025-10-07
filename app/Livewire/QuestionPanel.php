<?php

namespace App\Livewire;

use Livewire\Component;

class QuestionPanel extends Component
{
    public $activeSession;
    public $currentQuestion;

    public function mount()
    {
        $this->loadQuestion();
    }

    public function loadQuestion()
    {
        // Aquí cargas la pregunta activa de la sesión
        $this->currentQuestion = $this->activeSession?->current_question;
    }

    public function render()
    {
        return view('livewire.question-panel');
    }
}
