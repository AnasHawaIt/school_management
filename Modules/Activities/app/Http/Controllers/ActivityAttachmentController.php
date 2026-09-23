<?php

namespace Modules\Activities\app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Activities\app\Entities\Activity;
use Modules\Activities\app\Entities\ActivityAttachment;
use Modules\Activities\app\Http\Requests\UploadActivityAttachmentRequest;
use Modules\Activities\app\Services\ActivityAttachmentService;

class ActivityAttachmentController extends Controller
{
    public function __construct(
        protected ActivityAttachmentService $attachmentService
    ) {
    }

    /**
     * Upload activity attachment.
     */
    public function store(
        UploadActivityAttachmentRequest $request,
        Activity $activity
    ): JsonResponse {
        $data = $request->validated();

        $attachment = $this->attachmentService->upload(
            $activity,
            $data['file'],
            auth()->id(),
            $data['title'] ?? null,
            $data['description'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment->load('uploader'),
        ], 201);
    }

    /**
     * Delete activity attachment.
     */
    public function destroy(
        ActivityAttachment $attachment
    ): JsonResponse {
        $deleted = $this->attachmentService->delete(
            $attachment
        );

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
            'data' => [
                'deleted' => $deleted,
            ],
        ]);
    }
}
