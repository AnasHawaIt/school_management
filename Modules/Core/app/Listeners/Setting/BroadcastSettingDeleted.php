<?php

namespace Modules\Core\app\Listeners\Setting;


use Modules\Core\app\Events\Broadcasted\SettingBroadcast;
use Modules\Core\app\Events\Setting\SettingDeleted;

class BroadcastSettingDeleted
{
    public function handle(SettingDeleted $event): void
    {
        event(new SettingBroadcast(
            setting: $event->setting,
            action: 'deleted'
        ));
    }
}
