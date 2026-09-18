<?php

namespace Modules\Core\app\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Role;
use Modules\Core\app\Entities\User;

class UserRoleRemoved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public Role $role,
        public ?int $userId = null,
    ) {
        $this->userId ??= auth()->id();
    }
}
