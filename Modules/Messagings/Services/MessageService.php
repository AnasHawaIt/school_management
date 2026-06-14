<?php

namespace Modules\Messagings\Services;

use App\Services\ImageService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\DB;
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
        return $this->repo->getAnnouncementOnlyTrashed();
    }

    public function restore($id)
    {
        $author= $this->repo->restore($id);


        event(new AnnouncementRestored($author));

        return $author;
    }

    public function forceDelete($id)
    {
        $author= $this->repo->find($id);

        $author->forceDelete();

        event(new AnnouncementDeleted($author));

        return true;
    }

    public function send(array $data)
    {
        DB::beginTransaction();
        try {

            $message = $this->repo->create([
                'sender_id' => auth()->id(),
                'subject' => $data['subject'],
                'body' => $data['body'],
                'priority' => $data['priority'] ?? 'normal'
            ]);

            foreach ($data['recipients'] as $recipientId) {

                $message->recipients()->create([
                    'recipient_id' => $recipientId
                ]);
            }

            DB::commit();

            event(new MessageSent($message));

            return $message;

        } catch (\Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function markAsRead(int $messageId, int $userId)
    {
        return $this->repo->markAsRead($messageId, $userId);
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

    public function reply(int $messageId, array $data)
    {
        $message = $this->repo->find($messageId);

        return $this->send([
            'subject' => 'RE: '.$message->subject,
            'body' => $data['body'],
            'recipients' => [$message->sender_id]
        ]);
    }

    public function forward(int $messageId, array $recipients)
    {
        $message = $this->repo->find($messageId);

        return $this->send([
            'subject' =>
                'FW: '.$message->subject,

            'body' =>
                $message->body,

            'recipients' =>
                $recipients
        ]);
    }

    public function unreadCount(int $userId)
    {
        return $this->repo->unreadCount($userId);
    }

    public function delete(int $id): void
    {
        $message = $this->repo->find($id);

        $this->imageService->deleteAll($message);

        $this->repo->delete($id);

//        event(new AnnouncementDeleted($message));
    }
}
