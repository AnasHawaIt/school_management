<?php

namespace Modules\Library\Listeners\FineListeners;

use Modules\Library\Events\FinesEvents\FinePaid;
use Modules\Notifications\Services\NotificationService;

class NotifyFinePaid
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function handle(FinePaid $event): void
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
            title: 'Library Fine Paid',
            body: "Your library fine of {$fine->amount} has been paid.",
            type: 'library.fine.paid',
            data: [
                'fine_id' => $fine->id,
                'transaction_id' => $fine->transaction_id,
                'amount' => (string) $fine->amount,
            ]
        );
    }
}
