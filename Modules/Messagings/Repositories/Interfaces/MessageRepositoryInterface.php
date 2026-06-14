<?php
namespace Modules\Messagings\Repositories\Interfaces;

interface MessageRepositoryInterface
{
    public function getInbox(int $userId);
    public function unreadCount(int $userId);
    public function getSent(int $userId);
    public function markAsRead(int $messageId, int $userId);
    public function getAnnouncementOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function find($id);
    public function create(array $data);
    public function delete($id);
}
