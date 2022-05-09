<?php

namespace App\Events;

use App\Models\Attend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmployeePunchIn
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attend;

    public $attendDate;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Attend $attend, $date = null)
    {
        $this->attend = $attend;
        if (!$date) {
            $this->attendDate = $date;
        } else {
            $this->attendDate = date('Y-m-d', strtotime(now()));
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
