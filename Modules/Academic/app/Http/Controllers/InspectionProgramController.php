<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\app\Contracts\Services\InspectionProgramServiceInterface;
use Modules\Academic\app\Http\Requests\StoreInspectionProgramRequest;
use Modules\Academic\app\Http\Resources\InspectionProgramResource;

class InspectionProgramController extends Controller
{
    public function __construct(protected InspectionProgramServiceInterface $programService) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->programService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => InspectionProgramResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    public function store(StoreInspectionProgramRequest $request): JsonResponse
    {
        $program = $this->programService->createProgram($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Inspection program created successfully.',
            'data'    => new InspectionProgramResource($program),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new InspectionProgramResource($this->programService->getProgram($id)),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $program = $this->programService->updateProgram($id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Program updated successfully.',
                'data'    => new InspectionProgramResource($program),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $this->programService->deleteProgram($id);
        return response()->json(['success' => true, 'message' => 'Program deleted successfully.']);
    }

    public function restore(int $id): JsonResponse
    {
        $this->programService->restoreProgram($id);
        return response()->json(['success' => true, 'message' => 'Program restored successfully.']);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate(['status' => 'required|in:pending,ongoing,completed,cancelled']);
        $program = $this->programService->updateStatus($id, $request->status);
        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'data'    => new InspectionProgramResource($program),
        ]);
    }

    public function assignCounselor(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'counselor_id' => 'required|exists:counselors,id',
            'role'         => 'nullable|in:lead,member',
        ]);
        $this->programService->assignCounselor($id, $data['counselor_id'], $data['role'] ?? 'member');
        return response()->json(['success' => true, 'message' => 'Counselor assigned.']);
    }

    public function unassignCounselor(int $id, int $counselorId): JsonResponse
    {
        $this->programService->unassignCounselor($id, $counselorId);
        return response()->json(['success' => true, 'message' => 'Counselor unassigned.']);
    }

    public function submitObservation(Request $request, int $id): JsonResponse
    {

        $data = $request->validate([
            'objectives'  => 'required|string',
            'result'       => 'nullable|in:excellent,good,average,weak',
        ]);
        $data['counselor_id'] = auth()->id();
        $counselorId = $data['counselor_id'];
        unset($data['counselor_id']);

        $this->programService->submitObservation($id, $counselorId, $data);
        return response()->json(['success' => true, 'message' => 'Observation submitted.']);
    }

    public function sectionPrograms(Request $request, int $sectionId): JsonResponse
    {
        $programs = $this->programService->getSectionPrograms($sectionId, $request->all());
        return response()->json([
            'success' => true,
            'data'    => InspectionProgramResource::collection($programs),
        ]);
    }

    public function counselorPrograms(Request $request, int $counselorId): JsonResponse
    {
        $programs = $this->programService->getCounselorPrograms($counselorId, $request->all());
        return response()->json([
            'success' => true,
            'data'    => InspectionProgramResource::collection($programs),
        ]);
    }
    public function setCurrent(int $id): JsonResponse
    {
        try {
            $this->programService->setCurrent($id);

            return response()->json([
                'success' => true,
                'message' => 'inspection program set as current successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function currentCounselorProgram(): JsonResponse
    {
        $counselor = \Modules\Academic\app\Entities\Counselor::where('user_id', auth()->id())->firstOrFail();
        $program = $this->programService->getCurrentCounselorProgram($counselor->id);

        return response()->json([
            'success' => true,
            'data' => $program
                ? new InspectionProgramResource($program)
                : null,
        ]);
    }
}
