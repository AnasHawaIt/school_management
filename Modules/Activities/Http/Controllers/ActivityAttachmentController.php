<?php


namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Models\ActivityAttachment;
use Modules\Activities\Services\ActivityAttachmentService;

class ActivityAttachmentController extends Controller
{
    public function __construct(
        protected ActivityAttachmentService $attachmentService
    )
    {
    }

    /**
     * Upload attachment.
     */
    public function store(
        Request  $request,
        Activity $activity
    ): JsonResponse
    {

        $data = $request->validate([
            'file' => [
                'required',
                'file',
                'max:20480',
                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,mp4',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ]);

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
            'data' => $attachment,
        ], 201);
    }

    /**
     * Delete attachment.
     */
    public function destroy(
        ActivityAttachment $attachment
    ): JsonResponse
    {

        $this->attachmentService->delete(
            $attachment
        );

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
        ]);
    }
}
