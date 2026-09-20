<?php


namespace Modules\Core\app\Listeners\Setting;


use Modules\Core\app\Events\Broadcasted\SettingsBroadcast;
use Modules\Core\app\Events\Setting\SettingsUpdated;

class BroadcastSettingsUpdated
{
    public function handle(SettingsUpdated $event): void
    {
        event(new SettingsBroadcast(
            changes: $event->changes
        ));
    }
}
