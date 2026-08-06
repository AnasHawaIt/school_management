<?php

namespace Modules\Notifications\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Notifications\Entities\Notification;
use Modules\Notifications\Http\Requests\StoreNotificationRequest;
use Modules\Notifications\Services\NotificationService;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $notificationService
    ) {}

    public function index()
    {
        return response()->json(
            $this->notificationService->all(auth()->user()));
    }

    public function unread()
    {
        return response()->json(
            $this->notificationService->unread(auth()->user())
        );
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->notificationService->unreadCount(auth()->user())
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
        $count = $this->notificationService->markAllAsRead(
            auth()->user()
        );

        return response()->json([
            'message' => 'All notifications marked as read.',
            'updated' => $count,
        ]);
    }

    public function statistics(): array
    {
        return [
            'total' => Notification::count(),
            'pending' => Notification::pending()->count(),
            'sent' => Notification::sent()->count(),
            'failed' => Notification::failed()->count(),
        ];
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
                    users: $request->users,
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
