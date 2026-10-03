<?php

namespace Modules\Core\Listeners\Setting;

use Modules\Core\Events\Setting\SettingDeleted;

class LogSettingDeleted
{
    public function handle(SettingDeleted $event): void
    {
        $setting = $event->setting;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($setting)
            ->withProperties([
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'old_values' => $setting->toArray(),
            ])
            ->log('setting.deleted');
    }
}
