<?php

namespace Modules\Messagings\Services;

use Illuminate\Support\Facades\DB;
use Modules\Messagings\Entities\Conversation;
use Modules\Messagings\Entities\ConversationParticipant;
use Modules\Messagings\Entities\Message;
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
use Illuminate\Http\UploadedFile;

class MessageService
{
    protected $repo;
    protected MessageAttachmentService $attachmentService;

    public function __construct(MessageRepositoryInterface $repo, MessageAttachmentService $attachmentService) {
        $this->repo = $repo;
        $this->attachmentService = $attachmentService;
    }

    public function getMessageOnlyTrashed()
    {
        return $this->repo->getMessagesOnlyTrashed();
    }

    public function restore(int $id): Message
    {
        $message = $this->repo->findWithTrashed($id);

        $message->restore();

        $this->attachmentService->restoreAll($message);

        event(new MessageRestored($message,auth()->id()));

        return $message->load([
            'attachments',
            'sender',
            'conversation',
            'recipients',
            'statistic',
        ]);
    }

    public function forceDelete(int $id): bool
    {
        $message = $this->repo->findWithTrashed($id);

        $this->attachmentService->forceDeleteAll($message);

        return $message->forceDelete();
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

    public function sendVoice(
        int $conversationId,
        array $recipientIds,
        UploadedFile $file,
        ?int $duration = null
    ): Message {

        return DB::transaction(function () use (
            $conversationId,
            $recipientIds,
            $file,
            $duration
        ) {

            $senderId = auth()->id();

            $conversation = Conversation::findOrFail(
                $conversationId
            );

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

            $participantIds = $conversation
                ->participants()
                ->pluck('users.id')
                ->toArray();

            foreach ($recipientIds as $recipientId) {

                if (! in_array(
                    (int) $recipientId,
                    $participantIds
                )) {
                    abort(
                        422,
                        "User {$recipientId} is not a participant in this conversation."
                    );
                }
            }

            $message = $this->repo->create([
                'conversation_id' => $conversationId,
                'sender_id' => $senderId,
                'subject' => null,
                'body' => 'Voice message',
                'type' => 'voice',
                'priority' => 'normal',
            ]);


            $attachment = $this->attachmentService->uploadVoice(
                $message,
                $file,
                $duration
            );


            foreach ($recipientIds as $recipientId) {

                if ((int) $recipientId === (int) $senderId) {
                    continue;
                }

                $message->recipients()->create([
                    'recipient_id' => $recipientId,
                    'is_read' => false,
                ]);
            }


            $conversation->update([
                'last_message_at' => now(),
            ]);

            $message->statistic()->create([
                'sender_id' => $senderId,
                'read_count' => 0,
                'reply_count' => 0,
                'forward_count' => 0,
            ]);


            event(
                new MessageCreated(
                    $message,
                    $senderId
                )
            );

            return $message->load([
                'sender',
                'conversation',
                'recipients',
                'attachments',
                'statistic',
            ]);
        });
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

    public function find(int $id): Message
    {
        return $this->repo->find($id);
    }

    public function findWithTrashed(int $id): Message
    {
        return $this->repo->findWithTrashed($id);
    }

    public function markAsRead(int $messageId, int $userId)
    {
        return DB::transaction(function () use ($messageId, $userId) {

            $message = Message::with([
                'conversation',
                'sender',
            ])->findOrFail($messageId);

            /*
             * 1. التأكد أن المستخدم Participant
             */
            $participant = ConversationParticipant::query()
                ->where('conversation_id', $message->conversation_id)
                ->where('user_id', $userId)
                ->firstOrFail();

            /*
             * 2. البحث عن Recipient الخاص بهذا المستخدم
             */
            $recipient = $message->recipients()
                ->where('recipient_id', $userId)
                ->first();

            $alreadyRead = false;

            /*
             * 3. إذا كان المستخدم Recipient للرسالة
             */
            if ($recipient) {

                /*
                 * أول قراءة فقط
                 */
                if (! $recipient->is_read) {

                    $recipient->update([
                        'is_read' => true,
                        'read_at' => now(),
                    ]);

                    /*
                     * إنشاء الإحصائية إن لم تكن موجودة
                     */
                    $statistic = MessageStatistic::firstOrCreate(
                        [
                            'message_id' => $message->id,
                        ],
                        [
                            'sender_id' => $message->sender_id,
                            'read_count' => 0,
                            'reply_count' => 0,
                            'forward_count' => 0,
                        ]
                    );

                    /*
                     * أول قراءة للرسالة
                     */
                    if (is_null($statistic->first_read_at)) {
                        $statistic->update([
                            'first_read_at' => now(),
                        ]);
                    }

                    /*
                     * زيادة العداد مرة واحدة فقط
                     */
                    $statistic->increment('read_count');

                } else {

                    $alreadyRead = true;

                    /*
                     * جلب الإحصائية الموجودة
                     */
                    $statistic = MessageStatistic::firstOrCreate(
                        [
                            'message_id' => $message->id,
                        ],
                        [
                            'sender_id' => $message->sender_id,
                            'read_count' => 0,
                            'reply_count' => 0,
                            'forward_count' => 0,
                        ]
                    );
                }

                /*
                 * مهم جدًا:
                 *
                 * last_read_at يتحدث في كل مرة
                 * حتى لو كانت الرسالة مقروءة سابقًا
                 */
                $statistic->update([
                    'last_read_at' => now(),
                ]);

            } else {

                /*
                 * المستخدم Participant لكنه ليس Recipient
                 *
                 * مثل بعض رسائل Group القديمة
                 */
                $statistic = MessageStatistic::where(
                    'message_id',
                    $message->id
                )->first();

                /*
                 * إذا لم توجد إحصائية ننشئها
                 */
                if (! $statistic) {
                    $statistic = MessageStatistic::create([
                        'message_id' => $message->id,
                        'sender_id' => $message->sender_id,
                        'read_count' => 0,
                        'reply_count' => 0,
                        'forward_count' => 0,
                        'first_read_at' => now(),
                        'last_read_at' => now(),
                    ]);
                } else {

                    /*
                     * آخر قراءة تتحدث كل مرة
                     */
                    $statistic->update([
                        'last_read_at' => now(),
                    ]);
                }
            }

            /*
             * 4. آخر قراءة للمستخدم داخل المحادثة
             *
             * تتحدث في كل استدعاء
             */
            $participant->update([
                'last_read_at' => now(),
            ]);

            /*
             * 5. Event
             */
            event(
                new MessageRead(
                    $message,
                    $userId
                )
            );

            /*
             * 6. إرجاع البيانات المحدثة
             */
            $message->load([
                'sender',
                'recipients',
                'conversation',
                'statistic',
            ]);

            return [
                'message' => $message,
                'recipient' => $recipient?->fresh(),
                'statistic' => $message->statistic?->fresh(),
                'participant' => $participant->fresh(),
                'already_read' => $alreadyRead,
            ];
        });
    }

    public function uploadAttachment($id, $file)
    {
        $message = $this->repo->find($id);

        $attachment = $this->attachmentService->upload(
            $message,
            $file
        );

        event(
            new AttachmentUploaded(
                $message,
                $attachment
            )
        );

        return $attachment;
    }

    public function ShowAttachment($id)
    {
        return $this->attachmentService->find($id);
    }

    public function deleteAttachment(int $id): bool
    {
        $attachment = $this->attachmentService->delete($id);

        event(
            new AttachmentDeleted($attachment)
        );

        return true;
    }

    public function reply(int $messageId, array $data)
    {
        $originalMessage = $this->repo->find($messageId);

        $reply = $this->send([
            'conversation_id' => $originalMessage->conversation_id,

            'subject' => 'RE: ' . $originalMessage->subject,

            'body' => $data['body'],

            'recipients' => [
                $originalMessage->sender_id,
            ],
        ]);

        event(
            new MessageReplied(
                $originalMessage,
                $reply,
                auth()->id()
            )
        );

        return $reply;
    }

    public function forward(int $messageId, array $recipients)
    {
        $originalMessage = $this->repo->find($messageId);

        $forwardedMessage = $this->send([
            'conversation_id' => $originalMessage->conversation_id,

            'subject' => 'FW: ' . $originalMessage->subject,

            'body' => $originalMessage->body,

            'recipients' => $recipients,
        ]);

        event(
            new MessageForwarded(
                $originalMessage,
                $forwardedMessage,
                auth()->id()
            )
        );

        return $forwardedMessage;
    }

    public function unreadCount(int $userId)
    {
        return $this->repo->unreadCount($userId);
    }

    public function delete(int $id): void
    {
        $message = $this->repo->find($id);

        $this->attachmentService->deleteAll($message);

        event(new MessagesDeleted($message,auth()->id()));

        $this->repo->delete($id);

    }
}
