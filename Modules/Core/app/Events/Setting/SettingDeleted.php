<?php

namespace Modules\Core\app\Events\Setting;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\Setting;

class SettingDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Setting $setting,
        public ?int $userId = null,
    ) {}
}
