<?php

namespace Modules\Notifications\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\User;
use Modules\Notifications\Entities\Notification;
use Modules\Notifications\Events\NotificationCreated;

class NotificationService
{
    public function send(
        User $user,
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Notification {
        return DB::transaction(function () use (
            $user,
            $title,
            $body,
            $type,
            $data
        ) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => $title,
                'body' => $body,
                'type' => $type,
                'data' => $data,
                'status' => 'pending',
            ]);

            event(new NotificationCreated($notification));

            return $notification;
        });
    }

    public function sendToUsers(
        array $userIds,
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Collection {
        $notifications = collect();

        User::query()
            ->whereIn('id', $userIds)
            ->where('is_active', true)
            ->each(function (User $user) use (
                &$notifications,
                $title,
                $body,
                $type,
                $data
            ) {
                $notifications->push(
                    $this->send(
                        $user,
                        $title,
                        $body,
                        $type,
                        $data
                    )
                );
            });

        return $notifications;
    }

    public function sendToRole(
        string $role,
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Collection {
        $notifications = collect();

        User::query()
            ->where('user_type', $role)
            ->where('is_active', true)
            ->chunkById(100, function ($users) use (
                &$notifications,
                $title,
                $body,
                $type,
                $data
            ) {
                foreach ($users as $user) {
                    $notifications->push(
                        $this->send(
                            $user,
                            $title,
                            $body,
                            $type,
                            $data
                        )
                    );
                }
            });

        return $notifications;
    }

    public function sendToAll(
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Collection {
        $notifications = collect();

        User::query()
            ->where('is_active', true)
            ->chunkById(100, function ($users) use (
                &$notifications,
                $title,
                $body,
                $type,
                $data
            ) {
                foreach ($users as $user) {
                    $notifications->push(
                        $this->send(
                            $user,
                            $title,
                            $body,
                            $type,
                            $data
                        )
                    );
                }
            });

        return $notifications;
    }

    public function all(User $user): Collection
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->get();
    }

    public function unread(User $user): Collection
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->latest()
            ->get();
    }

    public function unreadCount(User $user): int
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }


    public function find(int $id): Notification
    {
        return Notification::query()
            ->where('user_id', auth()->id())
            ->findOrFail($id);
    }


    public function markAsRead(int $id): Notification
    {
        $notification = $this->find($id);

        if (!$notification->is_read) {
            $notification->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return $notification->fresh();
    }


    public function markAllAsRead(User $user): int
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    public function delete(int $id): void
    {
        $notification = $this->find($id);

        $notification->delete();
    }

    public function forceDelete(int $id): void
    {
        $notification = Notification::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->forceDelete();
    }

    public function resend(int $id): Notification
    {
        $notification = $this->find($id);

        $notification->update([
            'status' => 'pending',
            'sent_at' => null,
            'error_message' => null,
        ]);

        event(new NotificationCreated($notification));

        return $notification->fresh();
    }
}
