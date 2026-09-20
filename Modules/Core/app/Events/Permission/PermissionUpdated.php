<?php

namespace Modules\Core\app\Events\Permission;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Permission;

class PermissionUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Permission $permission,
        public ?int $userId = null,
        public array $oldValues = [],
        public array $newValues = [],
    ) {
    }
}
