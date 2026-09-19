<?php

namespace Modules\Core\app\Listeners\Setting;


use Modules\Core\app\Events\Broadcasted\SettingBroadcast;
use Modules\Core\app\Events\Setting\SettingUpdated;

class BroadcastSettingUpdated
{
    public function handle(SettingUpdated $event): void
    {
        event(new SettingBroadcast(
            setting: $event->setting,
            action: 'updated',
            changes: [
                'old' => $event->oldValues,
                'new' => $event->newValues,
            ],
        ));
    }
}
