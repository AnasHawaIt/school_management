<?php
namespace Modules\Messagings\Repositories\Eloquent;

use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageRecipient;
use Modules\Messagings\Repositories\Interfaces\MessageRepositoryInterface;

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

    public function getInbox(int $userId)
    {
        return MessageRecipient::query()
            ->where('recipient_id', $userId)
            ->with([
                'message.sender',
                'message.attachments'
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
                'attachments'
            ])
            ->latest()
            ->paginate(20);
    }

    public function markAsRead(
        int $messageId,
        int $userId
    )
    {
        return MessageRecipient::query()
            ->where('message_id', $messageId)
            ->where('recipient_id', $userId)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
    }

    public function unreadCount(int $userId)
    {
        return MessageRecipient::query()
            ->where('recipient_id', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getAnnouncementOnlyTrashed()
    {
        return Message::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $announcement = Message::withTrashed()->findOrFail($id);
        $announcement->restore();

        return $announcement;
    }

    public function forceDelete($id)
    {
        $announcement = Message::withTrashed()->findOrFail($id);

        $announcement->forceDelete();

        return $announcement;
    }

    public function delete($id)
    {
        $announcement = $this->find($id);

        return $announcement->delete();
    }
}
