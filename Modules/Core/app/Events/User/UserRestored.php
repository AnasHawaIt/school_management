<?php

namespace Modules\Core\app\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;


class UserRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public ?int $userId = null,
    ) {
        $this->userId ??= $user->id;
    }
}
