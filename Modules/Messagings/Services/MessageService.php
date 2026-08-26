<?php

namespace Modules\Messagings\Services;

use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Events\AttachmentUploaded;
use Modules\Messagings\Events\MessageCreated;
use Modules\Messagings\Events\MessageForwarded;
use Modules\Messagings\Events\MessageRead;
use Modules\Messagings\Events\MessageReplied;
use Modules\Messagings\Events\MessageRestored;
use Modules\Messagings\Events\MessagesDeleted;
use Modules\Messagings\Repositories\Interfaces\MessageRepositoryInterface;

class MessageService
{
    protected $repo;
    protected $imageService;

    public function __construct(MessageRepositoryInterface $repo, ImageService $imageService) {
        $this->repo = $repo;
        $this->imageService = $imageService;
    }

    public function getMessageOnlyTrashed()
    {
        return $this->repo->getMessagesOnlyTrashed();
    }

    public function restore($id)
    {
        $Messages= $this->repo->restore($id);

        event(new MessageRestored($Messages));

        return $Messages;
    }

    public function forceDelete($id)
    {
        $Messages= $this->repo->find($id);

        event(new MessagesDeleted($Messages));

        $Messages->forceDelete();

        return true;
    }

    public function send(array $data)
    {
        DB::beginTransaction();

        try {
            $senderId = auth()->id();

            $conversation = Conversation::findOrFail(
                $data['conversation_id']
            );

            // المرسل يجب أن يكون عضواً في المحادثة
            $isParticipant = $conversation
                ->participants()
                ->where('users.id', $senderId)
                ->exists();

            if (! $isParticipant) {
                abort(403, 'You are not a participant in this conversation.');
            }

            $message = $this->repo->create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $senderId,
                'subject'         => $data['subject'],
                'body'            => $data['body'],
                'priority'        => $data['priority'] ?? 'normal',
            ]);

            foreach ($data['recipients'] as $recipientId) {

                $recipientIsParticipant = $conversation
                    ->participants()
                    ->where('users.id', $recipientId)
                    ->exists();

                if (! $recipientIsParticipant) {
                    abort(
                        422,
                        "User {$recipientId} is not a participant in this conversation."
                    );
                }

                if ($recipientId == $senderId) {
                    continue;
                }

                $message->recipients()->create([
                    'recipient_id' => $recipientId,
                    'is_read'      => false,
                ]);
            }

            $conversation->update([
                'last_message_at' => now(),
            ]);

            DB::commit();

            event(new MessageCreated($message, $senderId));

            return $message->load([
                'sender',
                'recipients',
                'conversation',
            ]);

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function markAsRead(int $messageId, int $userId)
    {
        $Messages=$this->repo->markAsRead($messageId, $userId);

        event(new MessageRead($Messages,$userId));

        return $Messages;
    }

    public function getInbox(int $userId)
    {
        return $this->repo->getInbox($userId);
    }

    public function getSent(int $userId)
    {
        return $this->repo->getSent($userId);
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function uploadAttachment($id, $file)
    {
        $message = $this->repo->uploadAttachment($id, $file);

        $image = $this->imageService->upload(
            $message,
            $file,
        );

        event(new AttachmentUploaded($message, $image));

        return $image;
    }

    public function deleteAttachment($id)
    {
        $image = $this->imageService->find($id);

        if (!$image) {
            return response()->json(['message' => 'image not found'], 404);
        }

        $this->imageService->delete($id);

        event(new AttachmentDeleted($image));

        return true;
    }

    public function reply(int $messageId, array $data)
    {
        $message = $this->repo->find($messageId);

        //Gate::authorize('reply', $message);

        $newMessage = $this->send([
            'conversation_id' => $message->conversation_id,

            'subject' => 'RE: ' . $message->subject,

            'body' => $data['body'],

            'recipients' => [
                $message->sender_id,
            ],
        ]);

        event(
            new MessageReplied(
                $newMessage,
                auth()->id()
            )
        );

        return $newMessage;
    }

    public function forward(int $messageId, array $recipients)
    {
        $message = $this->repo->find($messageId);

       // Gate::authorize('forward', $message);

        $newMessage = $this->send([
            'conversation_id' => $message->conversation_id,

            'subject' => 'FW: ' . $message->subject,

            'body' => $message->body,

            'recipients' => $recipients,
        ]);

        event(
            new MessageForwarded(
                $newMessage,
                auth()->id()
            )
        );

        return $newMessage;
    }

    public function unreadCount(int $userId)
    {
        return $this->repo->unreadCount($userId);
    }

    public function delete(int $id): void
    {
        $message = $this->repo->find($id);

       // Gate::authorize('delete', $message);

        $this->imageService->deleteAll($message);

        event(new MessagesDeleted($message));

        $this->repo->delete($id);


    }
}
