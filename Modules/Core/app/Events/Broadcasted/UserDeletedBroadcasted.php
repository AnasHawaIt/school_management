<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;

class UserDeletedBroadcasted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public ?int $userId = null,
    ) {
        $this->userId ??= $user->id;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('users'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.deleted';
    }

    public function broadcastWith(): array
    {
        return [
            'user_id' => $this->user->id,
        ];
    }
}
