<?php

namespace Modules\Messagings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Messagings\app\Requests\ForwardMessageRequest;
use Modules\Messagings\app\Requests\ReplyMessageRequest;
use Modules\Messagings\app\Requests\SendMessageRequest;
use Modules\Messagings\app\Resources\MessageResource;
use Modules\Messagings\Entities\Conversation;
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

    public function deleteAttachment(
        Conversation $conversation,
        int $id
    ) {
        $attachment = $this->messageService->ShowAttachment($id);

        $this->authorize(
            'delete',
            [$attachment, $conversation]
        );

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

    public function sendVoice(Request $request, int $conversation) {
        $conversationModel = $this->conversationService->find(
            $conversation
        );

        $this->authorize(
            'send',
            $conversationModel
        );

        $validated = $request->validate([
            'audio' => [
                'required',
                'file',
                'mimes:webm,ogg,mp3,wav,m4a,mp4',
                'max:10240',
            ],

            'duration' => [
                'nullable',
                'integer',
                'min:1',
                'max:3600',
            ],

            'recipients' => [
                'required',
                'array',
                'min:1',
            ],

            'recipients.*' => [
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ]);

        $message = $this->messageService->sendVoice(
            $conversation,
            $validated['recipients'],
            $request->file('audio'),
            $validated['duration'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Voice message sent successfully.',
            'data' => $message,
        ], 201);
    }

    public function restore(
        Conversation $conversation,
        int $id
    ) {
        $message = $this->messageService->findWithTrashed($id);

        // تأكد أن الرسالة تخص هذه المحادثة
        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }

        $this->authorize(
            'restore',
            [$message, $conversation]
        );

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

    public function forceDelete(
        Conversation $conversation,
        int $id
    ) {
        $message = $this->messageService->findWithTrashed($id);

        if (!$message) {
            abort(404, 'Message not found.');
        }

        if ((int) $message->conversation_id !== (int) $conversation->id) {
            abort(404, 'Message does not belong to this conversation.');
        }

        $this->authorize(
            'forceDelete',
            [$message, $conversation]
        );

        $this->messageService->forceDelete($id);

        return response()->json([
            'success' => true,
            'message' => 'Message permanently deleted successfully.',
        ]);
    }

    public function AllOnlyTrashed(
        Conversation $conversation
    ) {
        $this->authorize('viewTrashed', [Message::class, $conversation]);

        return MessageResource::collection(
          $this->messageService->getMessageOnlyTrashed($conversation->id)
        );

    }

    public function store(SendMessageRequest $request, int $conversation) {
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

    public function delete(
        Conversation $conversation,
        int $id
    ) {
        $message = $this->messageService->find($id);

        if ($message->conversation_id !== $conversation->id) {
            abort(404);
        }
        $this->authorize(
            'delete',
            [$message, $conversation]
        );

        $this->messageService->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Message deleted successfully.',
        ]);
    }
}
