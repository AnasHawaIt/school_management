<?php

namespace Modules\Library\app\Listeners\FineListeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\app\Events\FinesEvents\FineCreated;
use Modules\Notifications\app\Services\NotificationService;

class NotifyFineCreated implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function handle(FineCreated $event): void
    {
        $fine = $event->fine;

        $fine->loadMissing([
            'transaction.member.user',
        ]);

        $user = $fine->transaction?->member?->user;

        if (!$user) {
            return;
        }

        $this->notificationService->send(
            user: $user,
            title: 'New Library Fine',
            body: "A library fine of {$fine->amount} has been created.",
            type: 'library.fine.created',
            data: [
                'fine_id' => $fine->id,
                'transaction_id' => $fine->transaction_id,
                'amount' => (string) $fine->amount,
            ]
        );
    }
}
