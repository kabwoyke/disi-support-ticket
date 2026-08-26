<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $adminText;
    public string|int $chatId;

    /**
     * Create a new event instance.
     */
    public function __construct(string $adminText , int $chatId)
    {
        $this->adminText = $adminText;
        $this->chatId = $chatId;
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-chat.' . $this->chatId),
        ];
    }

    /**
     * Set explicit broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'AdminChat';
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'adminText' => $this->adminText,
            'chatId'    => $this->chatId,
        ];
    }
}
