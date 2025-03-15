<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\BulletinBoardPost;

class LikeUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    public function __construct(BulletinBoardPost $post)
    {
        $this->post = $post;
    }

    public function broadcastOn()
    {
        return new Channel('likes');
    }

    public function broadcastWith()
    {
        return [
            'post_id' => $this->post->id,
            'likes_count' => $this->post->likes()->count(),
        ];
    }
}
