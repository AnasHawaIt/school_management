<?php

namespace Modules\Messagings\Services;

use App\Services\ImageService;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Events\AttachmentUploaded;
use Modules\Messagings\Events\MessageCreated;
use Modules\Messagings\Events\MessageFailed;
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

            $message = $this->repo->create([
                'sender_id' => auth()->id(),
                'subject'   => $data['subject'],
                'body'      => $data['body'],
                'priority'  => $data['priority'] ?? 'normal',
            ]);

            foreach ($data['recipients'] as $recipientId) {
                $message->recipients()->create([
                    'recipient_id' => $recipientId,
                ]);
            }

            DB::commit();

            event(new MessageCreated($message,auth()->id()));

            return $message;

        } catch (\Throwable $e) {

            DB::rollBack();

            event(new MessageFailed($message, $e->getMessage()));

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
            $file,
            $message,
            'messages'
        );

        event(new AttachmentUploaded($message, $image));

        return $image;
    }

    public function deleteAttachment($id)
    {
        $image = $this->imageService->find($id);

        $this->imageService->delete($image);

        event(new AttachmentDeleted($image));

        return true;
    }

    public function reply(int $messageId, array $data)
    {
        $message = $this->repo->find($messageId);

        Gate::authorize('reply', $message);

        $message= $this->send([
            'subject' => 'RE: '.$message->subject,
            'body' => $data['body'],
            'recipients' => [$message->sender_id]
        ]);

        event(new MessageReplied($message,auth()->id()));

        return $message;
    }

    public function forward(int $messageId,  array $recipients)
    {
        $message = $this->repo->find($messageId);

        Gate::authorize('forward', $message);

        $message= $this->send([
            'subject' =>
                'FW: '.$message->subject,

            'body' =>
                $message->body,

            'recipients' =>
                $recipients
        ]);

        event(new MessageForwarded($message, auth()->id()));

        return $message;
    }

    public function unreadCount(int $userId)
    {
        return $this->repo->unreadCount($userId);
    }

    public function delete(int $id): void
    {
        $message = $this->repo->find($id);

        Gate::authorize('delete', $message);

        $this->imageService->deleteAll($message);

        $this->repo->delete($id);

        event(new MessagesDeleted($message));
    }
}
