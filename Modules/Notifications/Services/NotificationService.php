<?php

namespace Modules\Notifications\Services;


use Modules\Core\Entities\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Modules\Notifications\Entities\Notification;
use Modules\Notifications\Events\NotificationCreated;


class NotificationService
{

    public function sendToUsers(
        array $users,
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Collection {
        $notifications = collect();
        foreach ($users as $userId) {
            $user = User::find($userId);
            if (!$user) {
                continue;
            }
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
        return $notifications;
    }

    public function send(
        User $user,
        string $title,
        string $body,
        string $type,
        array $data = []
    ): Notification
    {
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
            ]);

            event(new NotificationCreated($notification));

            return $notification;
        });
    }

    public function sendToAll(
        string $title,
        string $body,
        string $type,
        array $data = []
    )
    {
        $notifications = collect();

        User::chunk(100, function ($users) use (
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

    public function sendToRole(
        string $role,
        string $title,
        string $body,
        string $type,
        array $data = []
    )
    {
        $users = User::query()
            ->where('user_type', $role)
            ->where('is_active', true)
            ->get();

        $notifications = collect();

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

        return $notifications;
    }

    public function all(User $user): Collection
    {
        return Notification::where('user_id', $user->id)
            ->latest()
            ->get();
    }

    public function unread(User $user)
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->latest()
            ->get();
    }

    public function unreadCount(User $user): int
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
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

        if ($notification->read_at === null) {
            $notification->update([
                'read_at' => now(),
            ]);
        }

        return $notification->fresh();
    }

    public function markAllAsRead(User $user): int
    {
        return Notification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->update([
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
            'error_message' => null,
        ]);

        event(new NotificationCreated($notification));

        return $notification->fresh();
    }
}
