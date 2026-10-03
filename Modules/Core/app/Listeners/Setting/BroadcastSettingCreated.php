<?php

namespace Modules\Core\Listeners\Setting;

use Modules\Core\Events\Broadcasted\SettingBroadcast;
use Modules\Core\Events\Setting\SettingCreated;

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
