<?php

namespace Modules\Core\app\Listeners\Setting;

use Modules\Core\app\Events\Setting\SettingDeleted;

class LogSettingDeleted
{
    public function handle(SettingDeleted $event): void
    {
        $setting = $event->setting;

        activity()
            ->causedBy($event->userId)
            ->performedOn($setting)
            ->withProperties([
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'old_values' => $setting->toArray(),
            ])
            ->log('setting.deleted');
    }
}
