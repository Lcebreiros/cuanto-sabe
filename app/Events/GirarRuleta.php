<?php
// app/Events/GirarRuleta.php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class GirarRuleta implements ShouldBroadcastNow
{
    public string $sessionCode;

    public function __construct(string $sessionCode)
    {
        $this->sessionCode = $sessionCode;
    }

    public function broadcastOn()
    {
        return new Channel('cuanto-sabe-overlay-' . $this->sessionCode);
    }

    public function broadcastAs()
    {
        return 'girar-ruleta';
    }
}
