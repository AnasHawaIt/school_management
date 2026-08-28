<?php

namespace Modules\Messagings\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;

class MessageAttachmentService
{
    public function upload(
        Message $message,
        UploadedFile $file
    ): MessageAttachment {

        $path = $file->store(
            'messages/' . $message->id,
            'public'
        );

        return MessageAttachment::create([
            'message_id' => $message->id,
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
        ]);
    }

    public function find(int $id): MessageAttachment
    {
        return MessageAttachment::findOrFail($id);
    }

    public function deleteAll(Message $message): void
    {
        $attachments = MessageAttachment::query()
            ->where('message_id', $message->id)
            ->get();

        foreach ($attachments as $attachment) {

            if (
                $attachment->file_path &&
                Storage::disk('public')->exists($attachment->file_path)
            ) {
                Storage::disk('public')->delete(
                    $attachment->file_path
                );
            }

            $attachment->delete();
        }
    }

    public function delete(int $id): MessageAttachment
    {
        $attachment = $this->find($id);

        if (
            $attachment->file_path &&
            Storage::disk('public')->exists($attachment->file_path)
        ) {
            Storage::disk('public')->delete(
                $attachment->file_path
            );
        }

        $attachment->delete();

        return $attachment;
    }
}
