<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GuestPointsUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $sessionId;
    public $points;
    public string $sessionCode;

    public function __construct($sessionId, $points, string $sessionCode)
    {
        $this->sessionId = $sessionId;
        $this->points = $points;
        $this->sessionCode = $sessionCode;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay-' . $this->sessionCode);
    }

    public function broadcastAs()
    {
        return 'GuestPointsUpdated';
    }
}
