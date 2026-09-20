<?php

namespace Modules\Core\app\Jobs\Role;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Core\app\Entities\User;
use Modules\Notifications\app\Services\NotificationService;

class SendRoleNotificationJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public int $roleId,
        public string $eventType,
        public string $title,
        public string $body,
        public ?int $userId = null,
        public array $permissionIds = [],
        public array $oldPermissionIds = [],
    ) {}

    public function handle(
        NotificationService $notificationService
    ): void {
        $data = [
            'role_id' => $this->roleId,
            'event' => $this->eventType,
        ];

        if (!empty($this->permissionIds)) {
            $data['permission_ids'] = $this->permissionIds;
        }

        if (!empty($this->oldPermissionIds)) {
            $data['old_permission_ids'] = $this->oldPermissionIds;
        }

        /*
         * Send the notification to active administrators.
         *
         * We intentionally do not use auth()->user()
         * because this Job may run from a queue worker.
         */
        User::query()
            ->where('user_type', 'admin')
            ->where('is_active', true)
            ->chunkById(100, function ($users) use (
                $notificationService,
                $data
            ) {
                foreach ($users as $user) {
                    $notificationService->send(
                        user: $user,
                        title: $this->title,
                        body: $this->body,
                        type: $this->eventType,
                        data: $data,
                    );
                }
            });
    }
}
