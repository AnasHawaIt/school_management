<?php

namespace Modules\Library\Listeners\FineListeners;


use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Library\Events\FinesEvents\FineWaived;
use Modules\Notifications\Services\NotificationService;

class NotifyFineWaived implements ShouldQueue
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function handle(FineWaived $event): void
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
            title: 'Library Fine Waived',
            body: "Your library fine of {$fine->amount} has been waived.",
            type: 'library.fine.waived',
            data: [
                'fine_id' => $fine->id,
                'transaction_id' => $fine->transaction_id,
                'amount' => (string) $fine->amount,
            ]
        );
    }
}
