<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\app\Contracts\Services\TimetableServiceInterface;
use Modules\Academic\app\Http\Requests\StoreTimetableRequest;
use Modules\Academic\app\Http\Resources\TimetableResource;

class TimetableController extends Controller
{
    public function __construct(
        protected TimetableServiceInterface $timetableService,
    ) {}

    // GET /timetables
    public function index(Request $request): JsonResponse
    {
        $result = $this->timetableService->getAllTimetables($request->all());
        return response()->json([
            'success' => true,
            'data'    => TimetableResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /timetables
    public function store(StoreTimetableRequest $request): JsonResponse
    {
        try {
            $entry = $this->timetableService->createEntry($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Timetable entry created successfully.',
                'data'    => new TimetableResource($entry),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // GET /timetables/{timetable}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new TimetableResource($this->timetableService->getTimetable($id)),
        ]);
    }

    // PUT /timetables/{timetable}
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $entry = $this->timetableService->updateEntry($id, $request->all());
            return response()->json([
                'success' => true,
                'message' => 'Timetable entry updated successfully.',
                'data'    => new TimetableResource($entry),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // DELETE /timetables/{timetable}
    public function destroy(int $id): JsonResponse
    {
        $this->timetableService->deleteEntry($id);
        return response()->json(['success' => true, 'message' => 'Timetable entry deleted successfully.']);
    }

    // GET /sections/{section}/timetable?semester_id=1
    public function sectionTimetable(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        return response()->json([
            'success' => true,
            'data'    => $this->timetableService->getSectionTimetable($sectionId, $request->semester_id),
        ]);
    }

    // GET /teachers/{teacher}/timetable?semester_id=1
    public function teacherTimetable(Request $request, int $teacherId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        return response()->json([
            'success' => true,
            'data'    => $this->timetableService->getTeacherTimetable($teacherId, $request->semester_id),
        ]);
    }
}
