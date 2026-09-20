<?php

namespace Modules\Core\app\Events\Broadcasted;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;

class UserUpdatedBroadcasted implements ShouldBroadcast
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
        return 'user.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'user_type' => $this->user->user_type,
                'is_active' => $this->user->is_active,
            ],
        ];
    }
}
