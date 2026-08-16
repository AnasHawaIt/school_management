<?php


namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivitySupervisor;
use Modules\Activities\Services\ActivityService;

class ActivitySupervisorController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    )
    {
    }

    /**
     * Add supervisor.
     */
    public function store(
        Request  $request,
        Activity $activity
    ): JsonResponse
    {

        $data = $request->validate([
            'teacher_id' => [
                'required',
                'integer',
                'exists:teachers,id',
            ],

            'role' => [
                'nullable',
                'string',
                'max:100',
            ],

            'is_primary' => [
                'sometimes',
                'boolean',
            ],

            'notes' => [
                'nullable',
                'string',
            ],
        ]);

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
            'data' => $supervisor,
        ], 201);
    }

    /**
     * Set supervisor as primary.
     */
    public function primary(
        ActivitySupervisor $supervisor
    ): JsonResponse
    {

        $supervisor = $this->activityService
            ->setPrimarySupervisor($supervisor);

        return response()->json([
            'success' => true,
            'message' => 'Primary supervisor changed successfully.',
            'data' => $supervisor,
        ]);
    }

    /**
     * Remove supervisor.
     */
    public function destroy(
        ActivitySupervisor $supervisor
    ): JsonResponse
    {

        $this->activityService
            ->removeSupervisor($supervisor);

        return response()->json([
            'success' => true,
            'message' => 'Supervisor removed successfully.',
        ]);
    }
}
