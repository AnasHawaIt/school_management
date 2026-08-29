<?php

namespace Modules\Messagings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Messagings\app\Requests\ForwardMessageRequest;
use Modules\Messagings\app\Requests\ReplyMessageRequest;
use Modules\Messagings\app\Requests\SendMessageRequest;
use Modules\Messagings\app\Resources\MessageResource;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;
use Modules\Messagings\Services\ConversationService;
use Modules\Messagings\Services\MessageService;

class MessageController extends Controller
{
    protected ConversationService $conversationService;
    protected MessageService $messageService;
    public function __construct(MessageService $messageService,ConversationService $conversationService)
    {
        $this->messageService = $messageService;
        $this->conversationService = $conversationService;

    }

    public function indexAttachment()
    {
        $Attachment = MessageAttachment::query()->get();

        if (!$Attachment) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found Any Attachment '
            ], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $Attachment->count(),
            'iamgess' => $Attachment
        ], 200);
    }

    public function uploadAttachment(Request $request, int $message) {
        $request->validate([
            'attachment' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf,doc,docx',
                'max:5120',
            ],
        ]);

        $attachment = $this->messageService->uploadAttachment(
            $message,
            $request->file('attachment')
        );

        return response()->json([
            'success' => true,
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment,
        ], 201);
    }

    public function ShowAttachment(int $id)
    {
        $attachment = $this->messageService->ShowAttachment($id);

        $this->authorize('view', $attachment);

        return response()->json([
            'success' => true,
            'message' => 'Attachment retrieved successfully.',
            'data' => $attachment
        ]);
    }

    public function deleteAttachment(int $id)
    {
        $attachment = $this->messageService->ShowAttachment($id);

        $this->authorize('delete', $attachment);

        $this->messageService->deleteAttachment($id);

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
        ]);
    }

    public function inbox(int $conversation)
    {
        $messages = $this->messageService->getInbox(
            $conversation,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    public function index()
    {
        $messages = $this->messageService->getIndex(
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'data' => $messages,
        ]);
    }

    public function restore(int $id)
    {
        $message = $this->messageService->findWithTrashed($id);

        $this->authorize('restore', $message);

        $message = $this->messageService->restore($id);

        return response()->json([
            'success' => true,
            'message' => 'Message restored successfully.',
            'data' => $message,
        ]);
    }

    public function unreadCount()
    {
        return response()->json([
            'success' => true,
            'count' => $this->messageService->unreadCount(
                auth()->id()
            ),
        ]);
    }

    public function forceDelete(int $id)
    {
        $message = $this->messageService->findWithTrashed($id);

        $this->authorize('forceDelete', $message);

        $this->messageService->forceDelete($id);

        return response()->json([
            'success' => true,
            'message' => 'Message permanently deleted successfully.',
        ]);
    }

    public function AllOnlyTrashed()
    {
        $this->authorize('viewTrashed', Message::class);

        return MessageResource::collection(
            $this->messageService->getMessageOnlyTrashed()
        );
    }

    public function store(
        SendMessageRequest $request,
        int $conversation
    ) {
        $conversationModel = $this->conversationService->find(
            $conversation
        );

        $this->authorize(
            'send',
            $conversationModel
        );

        $data = $request->validated();

        $data['conversation_id'] = $conversation;
        $data['sender_id'] = auth()->id();

        $message = $this->messageService->send($data);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $message,
        ], 201);
    }

    public function getSent()
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

        $this->authorize('view', $message);

        return response()->json([
            'success' => true,
            'data' => $message,
        ]);
    }

    public function markAsRead(int $messageId)
    {
        $message = $this->messageService->find($messageId);

        $this->authorize('markAsRead', $message);

        $result = $this->messageService->markAsRead(
            $messageId,
            auth()->id()
        );

        return response()->json([
            'success' => true,
            'message' => 'Message marked as read.',
            'data' => $result,
        ]);
    }

    public function reply(int $id, ReplyMessageRequest $request)
    {
        $message = $this->messageService->find($id);

        $this->authorize('reply', $message);

        $reply = $this->messageService->reply(
            $id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully.',
            'data' => $reply,
        ]);
    }

    public function forward(int $id, ForwardMessageRequest $request) {
        $message = $this->messageService->find($id);

        $this->authorize('forward', $message);

        $forwarded = $this->messageService->forward(
            $id,
            $request->validated()['recipients']
        );

        return response()->json([
            'success' => true,
            'message' => 'Message forwarded successfully.',
            'data' => $forwarded,
        ]);
    }

    public function delete(int $id)
    {
        $message = $this->messageService->find($id);

        $this->authorize('delete', $message);

        $this->messageService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.',
        ]);
    }
}
