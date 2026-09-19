<?php

namespace Modules\Core\app\Jobs\Permission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;
use Modules\Notifications\app\Services\NotificationService;

class SendPermissionNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public string $title,
        public string $body,
        public string $type,
        public array $data = [],
    ) {}

    public function handle(
        NotificationService $notificationService
    ): void {
        User::query()
            ->where('user_type', 'admin')
            ->where('is_active', true)
            ->chunkById(100, function ($users) use ($notificationService) {
                foreach ($users as $user) {
                    $notificationService->send(
                        user: $user,
                        title: $this->title,
                        body: $this->body,
                        type: $this->type,
                        data: $this->data,
                    );
                }
            });
    }
}
