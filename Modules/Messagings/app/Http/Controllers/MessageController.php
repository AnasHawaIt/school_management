<?php

namespace Modules\Messagings\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Images;
use Illuminate\Http\Request;
use Modules\Messagings\app\Requests\ForwardMessageRequest;
use Modules\Messagings\app\Requests\ReplyMessageRequest;
use Modules\Messagings\app\Requests\SendMessageRequest;
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

    public function indexAttachment()
    {
        $iamges = Images::query()->get();

        if (!$iamges) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found Any iamges '
            ], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $iamges->count(),
            'iamgess' => $iamges
        ], 200);
    }

    public function uploadAttachment(Request $request, int $message)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        $attachment = $this->messageService->uploadAttachment(
            $message,
            $request->file('attachment')
        );

        return response()->json([
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment,
        ], 201);
    }

    public function deleteAttachment($id)
    {
        $this->messageService->deleteAttachment($id);

        return response()->json([
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

    public function restore($id)
    {
        return new MessageResource( $this->messageService->restore($id));
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

    public function store(SendMessageRequest $request, int $conversation) {
        $data = $request->validated();

        // conversation_id يأتي من URL
        $data['conversation_id'] = $conversation;

        $message = $this->messageService->send($data);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully',
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

        return new MessageDetailsResource($message);
    }

    public function markAsRead(int $messageId)
    {
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

    public function reply(int $messageId, ReplyMessageRequest $request)
    {
        $message = $this->messageService->reply(
            $messageId,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Reply sent successfully.',
            'data' => $message,
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
