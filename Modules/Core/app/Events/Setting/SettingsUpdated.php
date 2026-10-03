<?php

namespace Modules\Core\Events\Setting;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SettingsUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public array $changes,
        public ?int $userId = null,
    ) {}
}
