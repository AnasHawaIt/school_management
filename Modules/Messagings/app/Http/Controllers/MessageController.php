<?php

namespace Modules\Messagings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Messagings\app\Requests\ForwardMessageRequest;
use Modules\Messagings\app\Requests\ReplyMessageRequest;
use Modules\Messagings\app\Requests\SendMessageRequest;
use Modules\Messagings\app\Resources\InboxResource;
use Modules\Messagings\app\Resources\MessageDetailsResource;
use Modules\Messagings\app\Resources\MessageResource;
use Modules\Messagings\Services\MessageService;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;

    }

    public function restore($id)
    {
        return new MessageResource( $this->messageService->restore($id));
    }

    public function unreadCount()
    {
        return response()->json([
            'count' => $this->messageService
                ->unreadCount(auth()->id())
        ]);
    }

    public function forceDelete($id)
    {
        $this->messageService->forceDelete($id);

        return response()->json([
            'message' => 'Force deleted successfully'
        ]);
    }

    public function AllOnlyTrashed()
    {
        return MessageResource::collection(
            $this->messageService->getMessageOnlyTrashed()
        );

    }

    public function store(SendMessageRequest $request)
    {
        $message = $this->messageService
            ->send(
                $request->validated()
            );

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
            'data' => $message
        ]);
    }

    public function  inbox()
    {
        $messages = $this->messageService
                ->getInbox(auth()->id());

        return InboxResource::collection($messages);
    }

    public function sent()
    {
        $messages = $this->messageService
                ->getSent(
                    auth()->id()
                );

        return response()->json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function show(int $id)
    {
        $message = $this->messageService->find($id);

        return new MessageDetailsResource($message);
    }

    public function markAsRead(int $messageId)
    {
        $this->messageService->markAsRead($messageId, auth()->id());

        return response()->json([
            'success' => true
        ]);
    }

    public function reply(int $messageId, ReplyMessageRequest $request)
    {
        $message =
            $this->messageService
                ->reply(
                    $messageId,
                    $request->validated()
                );

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function forward(int $messageId, ForwardMessageRequest $request)
    {
        $message = $this->messageService->forward(
            $messageId,
            $request->validated()['recipients']
        );

        return response()->json([
            'success' => true,
            'data' => $message
        ]);
    }

    public function destroy($id)
    {
        $this->messageService->delete($id);

        return response()->json(['success', 'Message deleted!'],);
    }
}
