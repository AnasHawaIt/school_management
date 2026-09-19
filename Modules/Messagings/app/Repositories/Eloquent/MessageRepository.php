<?php
namespace Modules\Messagings\app\Repositories\Eloquent;

use Modules\Messagings\app\Entities\Message;
use Modules\Messagings\app\Entities\MessageRecipient;
use Modules\Messagings\app\Repositories\Interfaces\MessageRepositoryInterface;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(array $data)
    {
        return Message::create($data);
    }

    public function find($id): Message
    {
        return Message::with([
            'sender',
            'conversation',
            'recipients',
            'attachments',
            'statistic',
        ])->findOrFail($id);
    }

    public function findWithTrashed(int $id): Message
    {
        return Message::withTrashed()
            ->with([
                'sender',
                'conversation',
                'recipients',
                'attachments',
                'statistic',
            ])
            ->findOrFail($id);
    }

    public function getIndex(int $userId)
    {
        return MessageRecipient::query()
            ->where('recipient_id', $userId)
            ->where('is_deleted', false)
            ->with([
                'message.sender',
                'message.conversation',
                'message.attachments',
                'message.statistic'
            ])
            ->latest()
            ->paginate(20);
    }

    public function getInbox(int $conversationId, int $userId) {
        return MessageRecipient::query()
            ->where('recipient_id', $userId)
            ->where('is_deleted', false)
            ->whereHas('message', function ($query) use ($conversationId) {
                $query->where(
                    'conversation_id',
                    $conversationId
                );
            })
            ->with([
                'message.sender',
                'message.conversation',
                'message.attachments',
            ])
            ->latest()
            ->paginate(20);
    }

    public function getSent(int $userId)
    {
        return Message::query()
            ->where('sender_id', $userId)
            ->with([
                'recipients',
                'sender',
                'images'
            ])
            ->latest()
            ->paginate(20);
    }

    public function markAsRead(int $messageId, int $userId)
    {
        $recipient = MessageRecipient::query()
            ->where('message_id', $messageId)
            ->where('recipient_id', $userId)
            ->where('is_deleted', false)
            ->with([
                'message.conversation',
                'message.sender',
            ])
            ->firstOrFail();

        if (! $recipient->is_read) {
            $recipient->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return $recipient;
    }

    public function unreadCount(int $userId)
    {
        return MessageRecipient::query()
            ->forUser($userId)
            ->unread()
            ->where('is_deleted', false)
            ->count();
    }

    public function getMessagesOnlyTrashed()
    {
        return Message::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $message = Message::withTrashed()->findOrFail($id);

        $message->restore();

        return $message;
    }

    public function forceDelete($id)
    {

        $Messages = Message::withTrashed()->findOrFail($id);

        $Messages->forceDelete();

        return $Messages;
    }

    public function delete($id)
    {
        $Messages = $this->find($id);

        return $Messages->delete();
    }

}
