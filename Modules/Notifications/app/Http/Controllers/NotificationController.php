<?php

namespace Modules\Notifications\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Core\app\Entities\User;
use Modules\Notifications\app\Entities\Notification;
use Modules\Notifications\app\Http\Requests\StoreNotificationRequest;
use Modules\Notifications\app\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index()
    {
        $user =User::query()->find(auth()->id());

        return response()->json(
            $this->notificationService->all($user));
    }

    public function unread()
    {
        $user =User::query()->find(auth()->id());
        return response()->json(
            $this->notificationService->unread($user)
        );
    }

    public function unreadCount()
    {
        $user =User::query()->find(auth()->id());

        return response()->json([
            'count' => $this->notificationService->unreadCount($user)
        ]);
    }

    public function markAsRead($id)
    {
        return response()->json(
            $this->notificationService->markAsRead($id)
        );
    }

    public function markAllAsRead()
    {
        $user =User::query()->find(auth()->id());

        $count = $this->notificationService->markAllAsRead(
            $user
        );

        return response()->json([
            'message' => 'All notifications marked as read.',
            'updated' => $count,
        ]);
    }

    public function statistics()
    {
        return response()->json([
            'total' => Notification::query()->count(),
            'pending' => Notification::query()->pending()->count(),
            'sent' => Notification::query()->sent()->count(),
            'failed' => Notification::query()->failed()->count(),
        ]);
    }

    public function restore(int $id): Notification
    {
        $notification = Notification::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->restore();

        return $notification;
    }

    public function forceDelete($id)
    {
        $this->notificationService->forceDelete($id);

        return response()->json([
            'message' => 'Notification permanently deleted.'
        ]);
    }

    public function resend($id)
    {
        return response()->json(
            $this->notificationService->resend($id)
        );
    }

    public function store(StoreNotificationRequest $request)
    {
        switch ($request->target) {

            case 'users':

                $notifications = $this->notificationService->sendToUsers(
                    userIds: $request->users,
                    title: $request->title,
                    body: $request->body,
                    type: $request->type,
                    data: $request->input('data', [])
                );

                break;

            case 'role':

                $notifications = $this->notificationService->sendToRole(
                    role: $request->role,
                    title: $request->title,
                    body: $request->body,
                    type: $request->type,
                    data: $request->input('data', [])
                );

                break;

            case 'all':

                $notifications = $this->notificationService->sendToAll(
                    title: $request->title,
                    body: $request->body,
                    type: $request->type,
                    data: $request->input('data', [])
                );

                break;

            default:

                abort(422, 'Invalid target');

        }

        return response()->json([
            'message' => 'Notifications sent successfully.',
            'count' => $notifications->count(),
            'notifications' => $notifications,
        ], 201);
    }

    public function show($id)
    {
        return response()->json(
            $this->notificationService->find($id)
        );
    }

    public function destroy($id)
    {
        $this->notificationService->delete($id);

        return response()->json([
            'message' => 'Notification deleted successfully.'
        ]);
    }

}
