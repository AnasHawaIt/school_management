<?php

namespace Modules\Transport\Listeners\RouteListeners;

use Modules\Notifications\Services\NotificationService;
use Modules\Transport\Events\RouteEvents\RouteCreated;

class RouteCreatedNotificationDatabaseListener
{
    public function __construct(
        protected NotificationService $notificationService
    )
    {
    }

    public function handle(RouteCreated $event): void
    {
        $route = $event->route;

        $this->notificationService->sendToAll(
            title: 'new Rute',
            body: 'new route has been created. .',
            type: 'Transport',
            data: [
                'entity' => 'Route',
                'action' => 'Create',
                'Route_id' => $route->id,
            ]
        );
    }
}
