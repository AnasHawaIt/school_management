<?php

namespace Modules\Messagings\Services;

use App\Services\ImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\MessageStatistic;
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
        return DB::transaction(function () use ($data) {

            $senderId = auth()->id();

            /*
            |--------------------------------------------------------------------------
            | 1. Find conversation
            |--------------------------------------------------------------------------
            */

            $conversation = Conversation::findOrFail(
                $data['conversation_id']
            );

            /*
            |--------------------------------------------------------------------------
            | 2. Check sender is participant
            |--------------------------------------------------------------------------
            */

            $isSenderParticipant = $conversation
                ->participants()
                ->where('users.id', $senderId)
                ->exists();

            if (! $isSenderParticipant) {
                abort(
                    403,
                    'You are not a participant in this conversation.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 3. Create message
            |--------------------------------------------------------------------------
            */

            $message = $this->repo->create([
                'conversation_id' => $conversation->id,
                'sender_id'       => $senderId,
                'subject'         => $data['subject'] ?? null,
                'body'            => $data['body'],
                'priority'        => $data['priority'] ?? 'normal',
            ]);

            /*
            |--------------------------------------------------------------------------
            | 4. Get conversation participants
            |--------------------------------------------------------------------------
            */

            $participantIds = $conversation
                ->participants()
                ->pluck('users.id')
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | 5. Create recipients
            |--------------------------------------------------------------------------
            */

            foreach ($data['recipients'] as $recipientId) {

                /*
                 * Recipient must belong to conversation
                 */
                if (! in_array($recipientId, $participantIds)) {
                    abort(
                        422,
                        "User {$recipientId} is not a participant in this conversation."
                    );
                }

                /*
                 * Do not create recipient record for sender
                 */
                if ((int) $recipientId === (int) $senderId) {
                    continue;
                }

                $message->recipients()->create([
                    'recipient_id' => $recipientId,
                    'is_read'      => false,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | 6. Update conversation
            |--------------------------------------------------------------------------
            */

            $conversation->update([
                'last_message_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 7. Create statistics
            |--------------------------------------------------------------------------
            */

            $message->statistic()->create([
                'sender_id'     => $senderId,
                'read_count'    => 0,
                'reply_count'   => 0,
                'forward_count' => 0,
            ]);

            /*
            |--------------------------------------------------------------------------
            | 8. Event
            |--------------------------------------------------------------------------
            */

            event(
                new MessageCreated(
                    $message,
                    $senderId
                )
            );

            /*
            |--------------------------------------------------------------------------
            | 9. Return message
            |--------------------------------------------------------------------------
            */

            return $message->load([
                'sender',
                'conversation',
                'recipients',
                'statistic',
            ]);
        });
    }

    public function markAsRead(int $messageId, int $userId)
    {
        $Messages=$this->repo->markAsRead($messageId, $userId);

        event(new MessageRead($Messages,$userId));

        return $Messages;
    }

    public function getIndex(int $userId)
    {
        return $this->repo->getIndex($userId);
    }

    public function getInbox(int $conversationId, int $userId)
    {
        return $this->repo->getInbox(
            $conversationId,
            $userId
        );
    }

    public function getSent(int $userId)
    {
        return $this->repo->getSent($userId);
    }

    public function find(int $id)
    {
        return $this->repo->find($id);
    }

    public function statistic()
    {
        return $this->hasOne(
            MessageStatistic::class,
            'message_id'
        );
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
