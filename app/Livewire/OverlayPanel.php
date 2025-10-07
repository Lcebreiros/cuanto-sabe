<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OverlayPanel extends Component
{
    public $pregunta = 'Pregunta aún no enviada';
    public $opciones = []; // array of ['label'=>'A','texto'=>'...']
    public $seleccionada = null;

    protected $listeners = [
        'overlayQuestionReceived' => 'handleIncomingQuestion'
    ];

    public function mount()
    {
        //
    }

    public function handleIncomingQuestion($data)
    {
        // $data puede venir del frontend JS o un emit Livewire
        $this->pregunta = $data['pregunta'] ?? ($data['text'] ?? 'Pregunta aún no enviada');
        $this->opciones = $data['opciones'] ?? [];
        $this->seleccionada = null;
    }

    public function reveal()
    {
        try {
            Http::asJson()->post(route('game-session.reveal'), [
                '_token' => csrf_token()
            ]);
            $this->dispatchBrowserEvent('toast', ['message' => 'Respuesta revelada']);
        } catch (\Throwable $e) {
            Log::error('Error reveal: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error al revelar']);
        }
    }

    public function resetOverlay()
    {
        try {
            Http::asJson()->post(route('game-session.overlay-reset'), [
                '_token' => csrf_token()
            ]);
            $this->pregunta = 'Pregunta aún no enviada';
            $this->opciones = [];
            $this->seleccionada = null;
            $this->dispatchBrowserEvent('toast', ['message' => 'Overlay reiniciado']);
        } catch (\Throwable $e) {
            Log::error('Error resetOverlay: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error al reiniciar overlay']);
        }
    }

    public function selectOption($label)
    {
        $this->seleccionada = $label;
        try {
            Http::asJson()->post(route('game-session.select-option'), [
                '_token' => csrf_token(),
                'opcion' => $label
            ]);
        } catch (\Throwable $e) {
            Log::error('Error selectOption: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error enviando selección']);
        }
    }

    public function render()
    {
        return view('livewire.overlay-panel');
    }
}
