<?php

namespace Modules\Notifications\Events;


use Modules\Notifications\Entities\Notification;

class OrderAcceptedEvent
{

    public function __construct(
        protected Notification $notificationService
    )
    {
    }


    public function handle(OrderAcceptedEvent $event): void
    {

        $this->notificationService->send(
            user: $event->order->user,

            title: 'تم قبول الطلب',

            body: 'تم قبول طلبك بنجاح',

            type: 'Order',

            data: [
                'order_id' => $event->order->id,
            ]
        );

    }

}
