<?php


namespace Modules\Core\Listeners\Setting;


use Modules\Core\Events\Broadcasted\SettingsBroadcast;
use Modules\Core\Events\Setting\SettingsUpdated;

class BroadcastSettingsUpdated
{
    public function handle(SettingsUpdated $event): void
    {
        event(new SettingsBroadcast(
            changes: $event->changes
        ));
    }
}
