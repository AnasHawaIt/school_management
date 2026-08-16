<?php


namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Services\ActivityService;

class ActivityController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    )
    {
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
            ])
            ->when(
                $request->status,
                fn($query, $status) => $query->where('status', $status)
            )
            ->when(
                $request->category_id,
                fn($query, $categoryId) => $query->where('category_id', $categoryId)
            )
            ->latest()
            ->paginate(
                $request->integer('per_page', 15)
            );

        return response()->json([
            'success' => true,
            'message' => 'Activities retrieved successfully.',
            'data' => $activities,
        ]);
    }

    /**
     * Store a new activity.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'category_id' => [
                'required',
                'integer',
                'exists:activity_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_at' => [
                'required',
                'date',
            ],

            'end_at' => [
                'nullable',
                'date',
                'after:start_at',
            ],

            'registration_required' => [
                'boolean',
            ],

            'registration_deadline' => [
                'nullable',
                'date',
                'before_or_equal:start_at',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

        $activity = $this->activityService->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Activity created successfully.',
            'data' => $activity,
        ], 201);
    }

    /**
     * Display a specific activity.
     */
    public function show(Activity $activity): JsonResponse
    {
        $activity->load([
            'category',
            'participants',
            'supervisors',
            'attachments',
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
        Request  $request,
        Activity $activity
    ): JsonResponse
    {

        $data = $request->validate([
            'category_id' => [
                'sometimes',
                'integer',
                'exists:activity_categories,id',
            ],

            'title' => [
                'sometimes',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_at' => [
                'sometimes',
                'date',
            ],

            'end_at' => [
                'nullable',
                'date',
            ],

            'registration_required' => [
                'sometimes',
                'boolean',
            ],

            'registration_deadline' => [
                'nullable',
                'date',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ]);

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
        $activity = $this->activityService->publish(
            $activity
        );

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
        $activity = $this->activityService->cancel(
            $activity
        );

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
        $activity = $this->activityService->start(
            $activity
        );

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
        $activity = $this->activityService->complete(
            $activity
        );

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
        $this->activityService->delete(
            $activity
        );

        return response()->json([
            'success' => true,
            'message' => 'Activity deleted successfully.',
        ]);
    }
}
