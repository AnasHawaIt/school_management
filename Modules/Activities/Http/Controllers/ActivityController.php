<?php


namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Activities\app\Requests\StoreActivityRequest;
use Modules\Activities\app\Requests\UpdateActivityRequest;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Services\ActivityService;


class ActivityController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    ) {
    }

    /**
     * Display activities.
     */
    public function index(Request $request): JsonResponse
    {
        $activities = Activity::query()
            ->with([
                'category',
                'participants',
                'supervisors',
                'attachments',
            ])
            ->when(
                $request->filled('status'),
                fn ($query) =>
                $query->where('status', $request->status)
            )
            ->when(
                $request->filled('category_id'),
                fn ($query) =>
                $query->where('category_id', $request->category_id)
            )
            ->latest()
            ->paginate(
                min($request->integer('per_page', 15), 100)
            );

        return response()->json([
            'success' => true,
            'message' => 'Activities retrieved successfully.',
            'data' => $activities,
        ]);
    }

    /**
     * Store activity.
     */
    public function store(StoreActivityRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['created_by'] = auth()->id();

        $activity = $this->activityService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully.',
            'data' => $activity->load('category'),
        ], 201);
    }

    /**
     * Display activity.
     */
    public function show(Activity $activity): JsonResponse
    {
        $activity->load([
            'category',
            'creator',
            'participants.participant',
            'supervisors.teacher.user',
            'attachments.uploader',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Activity retrieved successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Update activity.
     */
    public function update(
        UpdateActivityRequest $request,
        Activity $activity
    ): JsonResponse {
        $data = $request->validated();

        $activity = $this->activityService->update(
            $activity,
            $data
        );

        return response()->json([
            'success' => true,
            'message' => 'Activity updated successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Publish activity.
     */
    public function publish(Activity $activity): JsonResponse
    {
        $activity = $this->activityService->publish($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity published successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Cancel activity.
     */
    public function cancel(Activity $activity): JsonResponse
    {
        $activity = $this->activityService->cancel($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity cancelled successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Start activity.
     */
    public function start(Activity $activity): JsonResponse
    {
        $activity = $this->activityService->start($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity started successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Complete activity.
     */
    public function complete(Activity $activity): JsonResponse
    {
        $activity = $this->activityService->complete($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity completed successfully.',
            'data' => $activity,
        ]);
    }

    /**
     * Delete activity.
     */
    public function destroy(Activity $activity): JsonResponse
    {
        $this->activityService->delete($activity);

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully.',
        ]);
    }
}
