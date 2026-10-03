<?php

namespace Modules\Activities\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivityAttachment;
use Modules\Activities\Events\ActivityAttachmentDeleted;
use Modules\Activities\Events\ActivityAttachmentUploaded;
use Modules\Activities\Repositories\Interfaces\ActivityAttachmentRepositoryInterface;

class ActivityAttachmentService
{
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',

        'application/pdf',

        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

        'video/mp4',
    ];

    /**
     * Maximum file size = 20 MB
     */
    protected int $maxFileSize = 20 * 1024;

    public function __construct(
        protected ActivityAttachmentRepositoryInterface $attachmentRepository
    ) {
    }

    /**
     * Upload activity attachment
     */
    public function upload(
        Activity $activity,
        UploadedFile $file,
        int $uploadedBy,
        ?string $title = null,
        ?string $description = null
    ): ActivityAttachment {

        // 1. Check uploaded file
        if (!$file->isValid()) {
            throw new \DomainException(
                'Uploaded file is not valid.'
            );
        }

        // 2. Check MIME type
        if (!in_array(
            $file->getMimeType(),
            $this->allowedMimeTypes,
            true
        )) {
            throw new \DomainException(
                'This file type is not allowed.'
            );
        }

        // 3. Check file size
        if ($file->getSize() > ($this->maxFileSize * 1024)) {
            throw new \DomainException(
                'File size exceeds the maximum allowed size.'
            );
        }

        // 4. Storage configuration
        $disk = 'public';

        $directory = "activities/{$activity->id}";

        // 5. Generate file name
        $fileName = $file->hashName();

        // 6. Store physical file
        $filePath = $file->store(
            $directory,
            $disk
        );

        try {

            // 7. Save attachment in database
            $attachment = DB::transaction(function () use (
                $activity,
                $file,
                $uploadedBy,
                $title,
                $description,
                $disk,
                $fileName,
                $filePath
            ) {
                return $this->attachmentRepository->create([
                    'activity_id' => $activity->id,

                    'uploaded_by' => $uploadedBy,

                    'original_name' =>
                        $file->getClientOriginalName(),

                    'file_name' =>
                        $fileName,

                    'file_path' =>
                        $filePath,

                    'disk' =>
                        $disk,

                    'mime_type' =>
                        $file->getMimeType(),

                    'file_size' =>
                        $file->getSize(),

                    'type' =>
                        $this->determineType($file),

                    'title' =>
                        $title,

                    'description' =>
                        $description,
                ]);
            });

            // 8. Dispatch Event AFTER database transaction
            ActivityAttachmentUploaded::dispatch(
                $attachment
            );

            // 9. Return attachment
            return $attachment;

        } catch (\Throwable $e) {

            // Database failed after physical file was stored
            Storage::disk($disk)->delete($filePath);

            throw $e;
        }
    }

    /**
     * Determine attachment type
     */
    protected function determineType(
        UploadedFile $file
    ): string {

        $mime = $file->getMimeType();

        return match (true) {

            str_starts_with($mime, 'image/')
            => 'image',

            str_starts_with($mime, 'video/')
            => 'video',

            in_array($mime, [
                'application/pdf',

                'application/msword',

                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',

                'application/vnd.ms-excel',

                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

            ], true)
            => 'document',

            default
            => 'other',
        };
    }

    /**
     * Delete activity attachment
     */
    public function delete(
        ActivityAttachment $attachment
    ): bool {

        $disk = $attachment->disk;

        $path = $attachment->file_path;

        // Keep the model instance before deletion
        $attachmentData = $attachment;

        $deleted = DB::transaction(function () use (
            $attachment,
            $disk,
            $path
        ) {

            $deleted = $this->attachmentRepository
                ->delete($attachment);

            if ($deleted) {

                Storage::disk($disk)
                    ->delete($path);
            }

            return $deleted;
        });

        // Dispatch Event after successful deletion
        if ($deleted) {

            ActivityAttachmentDeleted::dispatch(
                $attachmentData
            );
        }

        return $deleted;
    }
}
