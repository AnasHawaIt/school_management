<?php

namespace Modules\Core\app\Events\Permission;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Permission;

class PermissionRestored
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Permission $permission,
        public ?int $userId = null,
    ) {
    }
}
