<?php


namespace Modules\Core\app\Events\Role;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Role;

class RoleCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Role $role,
        public ?int $userId = null,
    )
    {
    }
}
