<?php

namespace Modules\Core\app\Listeners\Setting;

use Modules\Core\app\Events\Broadcasted\SettingBroadcast;
use Modules\Core\app\Events\Setting\SettingCreated;

class BroadcastSettingCreated
{
    public function handle(SettingCreated $event): void
    {
        event(new SettingBroadcast(
            setting: $event->setting,
            action: 'created'
        ));
    }
}
