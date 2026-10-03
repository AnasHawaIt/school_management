<?php

namespace Modules\Core\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\User;

class UserUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public ?int $userId = null,
    ) {
        $this->userId ??= $user->id;
    }
}
