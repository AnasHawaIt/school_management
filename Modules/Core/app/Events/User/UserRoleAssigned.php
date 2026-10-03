<?php

namespace Modules\Core\Events\User;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\User;

class UserRoleAssigned
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
