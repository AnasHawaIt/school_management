<?php

namespace Modules\Messagings\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Messagings\Entities\Message;
use Modules\Messagings\Entities\MessageAttachment;
use Modules\Messagings\Events\AttachmentDeleted;
use Modules\Messagings\Events\AttachmentUploaded;

class MessageAttachmentService
{
    /**
     * Upload normal attachment.
     */
    public function upload(
        Message $message,
        UploadedFile $file
    ): MessageAttachment {

        $path = $file->store(
            'messages/' . $message->id,
            'public'
        );

        $attachment = MessageAttachment::create([
            'message_id' => $message->id,
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new AttachmentUploaded(
            message: $message,
            attachment: $attachment
        ));

        return $attachment;
    }


    /**
     * Upload voice attachment.
     */
    public function uploadVoice(
        Message $message,
        UploadedFile $file,
        ?int $duration = null
    ): MessageAttachment {

        $path = $file->store(
            'messages/' . $message->id . '/voice',
            'public'
        );

        $attachment = MessageAttachment::create([
            'message_id' => $message->id,
            'file_name'  => $file->getClientOriginalName(),
            'file_path'  => $path,
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
            'duration'   => $duration,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */

        event(new AttachmentUploaded(
            message: $message,
            attachment: $attachment
        ));

        return $attachment;
    }


    /**
     * Find attachment.
     */
    public function find(
        int $id
    ): MessageAttachment {

        return MessageAttachment::findOrFail($id);
    }


    /**
     * Delete all attachments belonging to message.
     */
    public function deleteAll(
        Message $message
    ): void {

        $attachments = $message
            ->attachments()
            ->get();

        foreach ($attachments as $attachment) {

            $this->delete(
                $attachment->id
            );
        }
    }


    /**
     * Delete attachment.
     */
    public function delete(
        int $id
    ): MessageAttachment {

        $attachment = $this->find($id);

        /*
        |--------------------------------------------------------------------------
        | Delete physical file
        |--------------------------------------------------------------------------
        */

        if (
            $attachment->file_path &&
            Storage::disk('public')->exists(
                $attachment->file_path
            )
        ) {

            Storage::disk('public')->delete(
                $attachment->file_path
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Keep model instance for event
        |--------------------------------------------------------------------------
        */

        $attachment->load('message');

        /*
        |--------------------------------------------------------------------------
        | Delete database record
        |--------------------------------------------------------------------------
        */

        $attachment->delete();

        /*
        |--------------------------------------------------------------------------
        | Event
        |--------------------------------------------------------------------------
        */
        event(new AttachmentDeleted(
            attachmentId: $attachment->id,
            messageId: $attachment->message_id,
            fileName: $attachment->file_name,
            filePath: $attachment->file_path
        ));

        return $attachment;
    }


    /**
     * Restore all attachments.
     */
    public function restoreAll(
        Message $message
    ): void {

        $attachments = $message
            ->attachments()
            ->withTrashed()
            ->get();

        foreach ($attachments as $attachment) {

            $attachment->restore();
        }
    }


    /**
     * Permanently delete all attachments.
     */
    public function forceDeleteAll(
        Message $message
    ): void {

        $attachments = $message
            ->attachments()
            ->withTrashed()
            ->get();

        foreach ($attachments as $attachment) {

            if (
                $attachment->file_path &&
                Storage::disk('public')->exists(
                    $attachment->file_path
                )
            ) {

                Storage::disk('public')->delete(
                    $attachment->file_path
                );
            }

            $attachment->forceDelete();
        }
    }
}
