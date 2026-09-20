<?php

namespace Modules\Core\app\Listeners\Setting;

use Modules\Core\app\Events\Setting\SettingsUpdated;

class LogSettingsUpdated
{
    public function handle(SettingsUpdated $event): void
    {
        activity()
            ->causedBy(auth()->user())
            ->withProperties([
                'changes' => $event->changes,
            ])
            ->log('settings.updated');
    }
}
