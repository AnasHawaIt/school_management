<?php

namespace Modules\Transport\app\Listeners\RouteListeners;

use Modules\Notifications\app\Services\NotificationService;
use Modules\Transport\app\Events\RouteEvents\RouteCreated;

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
