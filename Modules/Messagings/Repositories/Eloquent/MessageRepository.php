<?php
namespace Modules\Messagings\Repositories\Eloquent;

use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageRecipient;
use Modules\Messagings\Repositories\Interfaces\MessageRepositoryInterface;
use Modules\Transport\Entities\images;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(array $data)
    {
        return Message::create($data);
    }

    public function find($id)
    {
        return Message::findOrFail($id);
    }

    public function uploadAttachment($id, $file)
    {
        $message = $this->find($id);

        return $message;
    }

    public function deleteAttachment($id)
    {
        $message = images::query()->find($id);

        return $message;
    }

    public function getInbox(int $userId)
    {
        return MessageRecipient::query()
            ->with([
                'message.sender',
                'message.images'
            ])
            ->where('recipient_id', $userId)
            ->latest()
            ->get();
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
        $message = Message::findOrFail($messageId);

        $message->recipients()
            ->where('recipient_id', $userId)
            ->update([
                'read_at' => now(),
            ]);

        return $message;
    }

    public function unreadCount(int $userId)
    {
        return MessageRecipient::query()
            ->where('recipient_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getMessagesOnlyTrashed()
    {
        return Message::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $Messages = Message::withTrashed()->findOrFail($id);
        $Messages->restore();

        return $Messages;
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
