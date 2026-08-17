<?php

namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Activities\app\Requests\AddSupervisorRequest;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivitySupervisor;
use Modules\Activities\Services\ActivityService;

class ActivitySupervisorController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    ) {
    }

    /**
     * Add supervisor.
     */
    public function store(
        AddSupervisorRequest $request,
        Activity $activity
    ): JsonResponse {
        $data = $request->validated();

        $supervisor = $this->activityService->addSupervisor(
            $activity,
            $data['teacher_id'],
            $data['role'] ?? null,
            $data['is_primary'] ?? false,
            $data['notes'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Supervisor added successfully.',
            'data' => $supervisor->load('teacher.user'),
        ], 201);
    }

    /**
     * Set supervisor as primary.
     */
    public function primary(
        ActivitySupervisor $supervisor
    ): JsonResponse {
        $supervisor = $this->activityService
            ->setPrimarySupervisor($supervisor);

        return response()->json([
            'success' => true,
            'message' => 'Primary supervisor changed successfully.',
            'data' => $supervisor->load('teacher.user'),
        ]);
    }

    /**
     * Remove supervisor.
     */
    public function destroy(
        ActivitySupervisor $supervisor
    ): JsonResponse {
        $deleted = $this->activityService
            ->removeSupervisor($supervisor);

        return response()->json([
            'success' => true,
            'message' => 'Supervisor removed successfully.',
            'data' => [
                'deleted' => $deleted,
            ],
        ]);
    }
}
