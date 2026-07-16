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
        // debug (no info): $data incluye label_correcto, no debe quedar en logs de nivel operativo
        \Log::debug('NuevaPreguntaOverlay lanzado', $data);
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

    public function broadcastWith()
    {
        // Nunca exponer la respuesta correcta en el canal público antes del reveal.
        $data = $this->data;
        unset($data['label_correcto']);

        return ['data' => $data];
    }
}
