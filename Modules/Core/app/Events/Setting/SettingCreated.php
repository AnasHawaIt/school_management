<?php

namespace Modules\Core\Events\Setting;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Entities\Setting;

class SettingCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Setting $setting,
        public ?int $userId = null,
    ) {}
}
