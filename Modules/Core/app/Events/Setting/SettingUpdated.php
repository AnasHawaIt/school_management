<?php

namespace Modules\Core\Events\Setting;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\Setting;

class SettingUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Setting $setting,
        public ?int $userId = null,
        public array $oldValues = [],
        public array $newValues = [],
    ) {}
}
