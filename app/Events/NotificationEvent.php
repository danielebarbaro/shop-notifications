<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $title;
    public $type;
    private $channel;
    private $broadcast;

    /**
     * NotificationEvent constructor.
     * @param $channel
     * @param $broadcast
     * @param $message
     * @param $title
     * @param $type
     */
    public function __construct($channel, $broadcast, $message, $title, $type)
    {
        $this->message = $message;
        $this->title = $title;
        $this->type = $type;
        $this->channel = $channel;
        $this->broadcast = $broadcast;
        Log::debug("NOTIFICATION > {$channel} {$broadcast} {$message} {$title} {$type}");
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return $this->broadcast;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
//        return new PrivateChannel('channel-name');
        return new Channel($this->channel);
    }
}
