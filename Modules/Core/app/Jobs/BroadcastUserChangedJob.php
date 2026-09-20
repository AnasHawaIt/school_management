<?php

namespace Modules\Core\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Core\app\Entities\User;
use Modules\Core\app\Events\Broadcasted\UserCreatedBroadcasted;
use Modules\Core\app\Events\Broadcasted\UserDeletedBroadcasted;
use Modules\Core\app\Events\Broadcasted\UserUpdatedBroadcasted;

class BroadcastUserChangedJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public int $userId,
        public string $eventType,
    ) {}

    public function handle(): void
    {
        $user = User::find($this->userId);

        if (!$user) {
            Log::warning('User broadcast skipped because user was not found.', [
                'user_id' => $this->userId,
                'event' => $this->eventType,
            ]);

            return;
        }

        match ($this->eventType) {

            'user.created' => event(
                new UserCreatedBroadcasted(
                    user: $user,
                    userId: $user->id,
                )
            ),

            'user.updated' => event(
                new UserUpdatedBroadcasted(
                    user: $user,
                    userId: $user->id,
                )
            ),

            'user.deleted' => event(
                new UserDeletedBroadcasted(
                    user: $user,
                    userId: $user->id,
                )
            ),

            default => Log::warning('Unknown user broadcast event.', [
                'user_id' => $this->userId,
                'event' => $this->eventType,
            ]),
        };

        Log::info('User broadcast dispatched.', [
            'user_id' => $this->userId,
            'event' => $this->eventType,
        ]);
    }
}
