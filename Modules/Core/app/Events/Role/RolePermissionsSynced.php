<?php

namespace Modules\Core\Events\Role;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\Role;

class RolePermissionsSynced
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Role $role,
        public array $oldPermissionIds,
        public array $newPermissionIds,
        public ?int $userId = null,
    ) {
    }
}
