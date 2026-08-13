<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Services\StudentPointService;

class StudentPointController extends Controller
{
    public function __construct(protected StudentPointService $pointService) {}


    public function index(Request $request): JsonResponse
    {
        $result = $this->pointService->getAll($request->all());
        return response()->json(['success' => true, 'data' => $result->items(), 'meta' => [
            'current_page' => $result->currentPage(),
            'last_page'    => $result->lastPage(),
            'total'        => $result->total(),
        ]]);
    }


    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_id'              => 'required|exists:students,id',
            'point_category_id'       => 'required|exists:point_categories,id',
            'academic_year_id'        => 'required|exists:academic_years,id',
            'semester_id'             => 'required|exists:semesters,id',
            'points'                  => 'nullable|integer|min:1',
            'reason'                  => 'required|string|max:500',
            'date'                    => 'nullable|date',
            'given_by_type'           => 'required|in:counselor,teacher',
            'given_by_id'             => 'required|integer',
            'inspection_program_id'   => 'nullable|exists:inspection_programs,id',
            'notes'                   => 'nullable|string|max:500',
        ]);

        $point = $this->pointService->givePoint($data);
        return response()->json(['success' => true, 'message' => 'Point assigned successfully.', 'data' => $point], 201);
    }

    public function bulk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'student_ids'             => 'required|array|min:1',
            'student_ids.*'           => 'required|exists:students,id',
            'point_category_id'       => 'required|exists:point_categories,id',
            'academic_year_id'        => 'required|exists:academic_years,id',
            'semester_id'             => 'required|exists:semesters,id',
            'points'                  => 'nullable|integer|min:1',
            'reason'                  => 'required|string|max:500',
            'date'                    => 'nullable|date',
            'given_by_type'           => 'required|in:counselor,teacher',
            'given_by_id'             => 'required|integer',
            'inspection_program_id'   => 'nullable|exists:inspection_programs,id',
            'notes'                   => 'nullable|string|max:500',
        ]);

        $this->pointService->bulkGive($data);
        return response()->json([
            'success' => true,
            'message' => count($data['student_ids']) . ' students assigned points successfully.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->pointService->deletePoint($id);
        return response()->json(['success' => true, 'message' => 'Point deleted successfully.']);
    }


    public function studentTotal(Request $request, int $studentId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $total = $this->pointService->getStudentTotal($studentId, $request->semester_id);
        return response()->json(['success' => true, 'data' => $total]);
    }


    public function studentHistory(Request $request, int $studentId): JsonResponse
    {
        $result = $this->pointService->getStudentHistory($studentId, $request->all());
        return response()->json(['success' => true, 'data' => $result->items(), 'meta' => [
            'current_page' => $result->currentPage(),
            'total'        => $result->total(),
        ]]);
    }


    public function sectionRanking(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $ranking = $this->pointService->getSectionRanking($sectionId, $request->semester_id);
        return response()->json(['success' => true, 'data' => $ranking]);
    }

    public function stats(Request $request): JsonResponse
    {
        $stats = $this->pointService->getStats($request->all());
        return response()->json(['success' => true, 'data' => $stats]);
    }
}
