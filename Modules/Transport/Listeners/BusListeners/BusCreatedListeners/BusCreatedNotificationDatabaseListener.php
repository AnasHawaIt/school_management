<?php

namespace Modules\Transport\Listeners\BusListeners\BusCreatedListeners;

use Modules\Notifications\Services\NotificationService;
use Modules\Transport\Events\BusEvents\BusCreated;

class BusCreatedNotificationDatabaseListener
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(BusCreated $event): void
    {
        $bus = $event->bus;

        $this->notificationService->sendToAll(
            title: 'new sbus',
            body: 'new bus has been created. .',
            type: 'Transport',
            data: [
                'entity' => 'Bus',
                'action' => 'Create',
                'bus_id' => $bus->id,
            ]
        );
    }

}
