<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TendenciaActualizada implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public string $sessionCode;

    public function __construct($data, string $sessionCode)
    {
        $this->data = $data;
        $this->sessionCode = $sessionCode;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay-' . $this->sessionCode);
    }

    public function broadcastAs()
    {
        return 'tendencia-actualizada';
    }
}
