<?php


namespace Modules\Activities\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Activities\Entities\Activity;
use Modules\Activities\Entities\ActivityParticipant;
use Modules\Activities\Services\ActivityService;

class ActivityParticipantController extends Controller
{
    public function __construct(
        protected ActivityService $activityService
    )
    {
    }

    /**
     * Register participant.
     */
    public function register(
        Request  $request,
        Activity $activity
    ): JsonResponse
    {

        $data = $request->validate([
            'participant_type' => [
                'required',
                'string',
                'in:student,teacher,parent',
            ],

            'participant_id' => [
                'required',
                'integer',
            ],

            'role' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        $participant = $this->activityService
            ->registerParticipant(
                $activity,
                $data['participant_type'],
                $data['participant_id'],
                $data['role'] ?? null
            );

        return response()->json([
            'success' => true,
            'message' => 'Participant registered successfully.',
            'data' => $participant,
        ], 201);
    }

    /**
     * Confirm participant.
     */
    public function confirm(
        ActivityParticipant $participant
    ): JsonResponse
    {

        $participant = $this->activityService
            ->confirmParticipant($participant);

        return response()->json([
            'success' => true,
            'message' => 'Participant confirmed successfully.',
            'data' => $participant,
        ]);
    }

    /**
     * Cancel participant.
     */
    public function cancel(
        ActivityParticipant $participant
    ): JsonResponse
    {

        $participant = $this->activityService
            ->cancelParticipant($participant);

        return response()->json([
            'success' => true,
            'message' => 'Participant cancelled successfully.',
            'data' => $participant,
        ]);
    }

    /**
     * Mark participant as attended.
     */
    public function attend(
        ActivityParticipant $participant
    ): JsonResponse
    {

        $participant = $this->activityService
            ->markAttendance($participant);

        return response()->json([
            'success' => true,
            'message' => 'Participant marked as attended.',
            'data' => $participant,
        ]);
    }

    /**
     * Mark participant as absent.
     */
    public function absent(
        ActivityParticipant $participant
    ): JsonResponse
    {

        $participant = $this->activityService
            ->markAbsent($participant);

        return response()->json([
            'success' => true,
            'message' => 'Participant marked as absent.',
            'data' => $participant,
        ]);
    }
}
