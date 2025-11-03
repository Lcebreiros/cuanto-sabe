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

    public function __construct($data)
    {
        \Log::info('NuevaPreguntaOverlay lanzado', $data);
        $this->data = $data;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay');
    }

    public function broadcastAs()
    {
        return 'nueva-pregunta';
    }
}
