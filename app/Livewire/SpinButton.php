<?php

namespace App\Livewire;

use Livewire\Component;

class SpinButton extends Component
{
    public $activeSession;

    public function spin()
    {
        // Aquí va tu lógica de ruleta
        $this->emit('spin'); // Emitimos evento JS
    }

    public function render()
    {
        return view('livewire.spin-button');
    }
}
