<?php

namespace Modules\Core\Listeners\Setting;


use Modules\Core\Events\Broadcasted\SettingBroadcast;
use Modules\Core\Events\Setting\SettingUpdated;

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
