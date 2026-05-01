<?php

// App\Events\OpcionSeleccionada.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class OpcionSeleccionada implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $opcion;
    public string $sessionCode;

    public function __construct($opcion, string $sessionCode)
    {
        $this->opcion = $opcion;
        $this->sessionCode = $sessionCode;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay-' . $this->sessionCode);
    }

    public function broadcastAs()
    {
        return 'opcion-seleccionada';
    }
}
