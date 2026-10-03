<?php

namespace Modules\Core\Listeners\Setting;


use Modules\Core\Events\Broadcasted\SettingBroadcast;
use Modules\Core\Events\Setting\SettingDeleted;

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
