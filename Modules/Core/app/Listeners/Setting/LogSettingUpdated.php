<?php

namespace Modules\Core\app\Listeners\Setting;

use Modules\Core\app\Events\Setting\SettingUpdated;

class LogSettingUpdated
{
    public function handle(SettingUpdated $event): void
    {
        $setting = $event->setting;

        activity()
            ->causedBy($event->userId)
            ->performedOn($setting)
            ->withProperties([
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'old_values' => $event->oldValues,
                'new_values' => $event->newValues,
            ])
            ->log('setting.updated');
    }
}
