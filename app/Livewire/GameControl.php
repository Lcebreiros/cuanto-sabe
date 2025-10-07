<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\GameSession;

class GameControl extends Component
{
    public $activeSession;
    public $guest_name = '';
    public $motivo_id = '';
    public $modo = 'normal';
    public $isExpress = false;
    public $motivos;
    public $categorias;

    public function mount()
    {
        $this->refreshSession();
        $this->motivos = \App\Models\Motivo::all();
        $this->categorias = \App\Models\Categoria::with('motivo')->get();
    }

    public function refreshSession()
    {
        $this->activeSession = GameSession::where('status', 'active')->latest()->first();
    }

    public function startSession()
    {
        $this->validate([
            'guest_name' => 'required|string|max:255',
            'motivo_id' => 'required|exists:motivos,id',
        ]);

        // Llamamos a la ruta que ya tenés en web.php (game-session.start)
        try {
            $response = Http::asForm()->post(route('game-session.start'), [
                '_token' => csrf_token(),
                'guest_name' => $this->guest_name,
                'motivo_id' => $this->motivo_id,
                'modo_juego' => $this->modo,
            ]);
            // Actualizar estado local
            $this->refreshSession();
            $this->dispatchBrowserEvent('toast', ['message' => 'Sesión iniciada']);
            $this->resetForm();
        } catch (\Throwable $e) {
            Log::error('Error startSession: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error iniciando sesión']);
        }
    }

    public function endSession()
    {
        if (!$this->activeSession) {
            $this->dispatchBrowserEvent('toast', ['message' => 'No hay sesión activa']);
            return;
        }
        try {
            Http::asForm()->post(route('game-session.end'), [
                '_token' => csrf_token(),
            ]);
            $this->refreshSession();
            $this->dispatchBrowserEvent('toast', ['message' => 'Sesión finalizada']);
        } catch (\Throwable $e) {
            Log::error('Error endSession: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error finalizando sesión']);
        }
    }

    public function girarRuleta()
    {
        try {
            Http::asJson()->post(url('/game-session/girar-ruleta'), [
                '_token' => csrf_token(),
            ]);
            $this->dispatchBrowserEvent('toast', ['message' => 'Ruleta girada']);
        } catch (\Throwable $e) {
            Log::error('Error girarRuleta: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error al girar ruleta']);
        }
    }

    public function enviarPreguntaRandom($categoria_id)
    {
        try {
            Http::asJson()->post(route('game-session.random-question'), [
                '_token' => csrf_token(),
                'categoria_id' => $categoria_id,
            ]);
            $this->dispatchBrowserEvent('toast', ['message' => 'Pregunta random enviada']);
        } catch (\Throwable $e) {
            Log::error('Error enviarPreguntaRandom: '.$e->getMessage());
            $this->dispatchBrowserEvent('toast', ['message' => 'Error enviando pregunta']);
        }
    }

    public function copyOverlayUrl()
    {
        // Dispatch a browser event with the overlay URL for client-side clipboard copy
        $this->dispatchBrowserEvent('copy-overlay-url', ['url' => url('/overlay')]);
    }

    public function toggleMode()
    {
        $this->modo = $this->isExpress ? 'express' : 'normal';
    }

    public function resetForm()
    {
        $this->guest_name = '';
        $this->motivo_id = '';
        $this->isExpress = false;
        $this->modo = 'normal';
    }

    public function render()
    {
        return view('livewire.game-control');
    }
}
