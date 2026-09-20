<?php

namespace Modules\Core\app\Listeners\Setting;

use Modules\Core\app\Events\Setting\SettingCreated;

class LogSettingCreated
{
    public function handle(SettingCreated $event): void
    {
        $setting = $event->setting;

        activity()
            ->causedBy(auth()->user())
            ->performedOn($setting)
            ->withProperties([
                'setting_id' => $setting->id,
                'key' => $setting->key,
                'value' => $setting->value,
                'type' => $setting->type,
            ])
            ->log('setting.created');
    }
}
