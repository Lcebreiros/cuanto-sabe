<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class NuevaPreguntaOverlay implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $data;
    public string $sessionCode;

    public function __construct($data, string $sessionCode)
    {
        \Log::info('NuevaPreguntaOverlay lanzado', $data);
        $this->data = $data;
        $this->sessionCode = $sessionCode;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay-' . $this->sessionCode);
    }

    public function broadcastAs()
    {
        return 'nueva-pregunta';
    }
}
