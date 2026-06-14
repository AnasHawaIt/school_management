<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Contracts\Services\ExamResultServiceInterface;
use Modules\Examination\Http\Requests\BulkEnterResultsRequest;
use Modules\Examination\Http\Resources\ExamResultResource;

class ExamResultController extends Controller
{
    public function __construct(
        protected ExamResultServiceInterface $resultService,
    ) {}

    // GET /exam-results
    public function index(Request $request): JsonResponse
    {
        $result = $this->resultService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => ExamResultResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // GET /exams/{exam}/results
    public function examResults(int $examId): JsonResponse
    {
        $results = $this->resultService->getExamResults($examId);
        return response()->json([
            'success' => true,
            'data'    => ExamResultResource::collection($results),
        ]);
    }

    // POST /exam-results
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'exam_id'        => 'required|exists:exams,id',
            'student_id'     => 'required|exists:students,id',
            'marks_obtained' => 'nullable|numeric|min:0',
            'is_absent'      => 'nullable|boolean',
            'remarks'        => 'nullable|string|max:500',
        ]);
        $result = $this->resultService->enterResult($data);
        return response()->json([
            'success' => true,
            'message' => 'Result entered successfully.',
            'data'    => new ExamResultResource($result),
        ], 201);
    }

    // POST /exams/{exam}/results/bulk
    public function bulk(BulkEnterResultsRequest $request, int $examId): JsonResponse
    {
        $this->resultService->bulkEnter($examId, $request->validated()['results']);
        return response()->json([
            'success' => true,
            'message' => 'Results entered successfully. Exam marked as completed.',
        ]);
    }

    // GET /exam-results/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ExamResultResource($this->resultService->getResult($id)),
        ]);
    }

    // PUT /exam-results/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'marks_obtained' => 'nullable|numeric|min:0',
            'is_absent'      => 'nullable|boolean',
            'remarks'        => 'nullable|string|max:500',
        ]);
        $result = $this->resultService->updateResult($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Result updated successfully.',
            'data'    => new ExamResultResource($result),
        ]);
    }

    // GET /exams/{exam}/stats
    public function examStats(int $examId): JsonResponse
    {
        $stats = $this->resultService->getExamStats($examId);
        return response()->json(['success' => true, 'data' => $stats]);
    }

    // GET /students/{student}/results
    public function studentResults(Request $request, int $studentId): JsonResponse
    {
        $results = $this->resultService->getStudentResults($studentId, $request->all());
        return response()->json([
            'success' => true,
            'data'    => ExamResultResource::collection($results->items()),
            'meta'    => [
                'current_page' => $results->currentPage(),
                'last_page'    => $results->lastPage(),
                'total'        => $results->total(),
            ],
        ]);
    }

    // GET /students/{student}/exam-stats?semester_id=1
    public function studentStats(Request $request, int $studentId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $stats = $this->resultService->getStudentStats($studentId, $request->semester_id);
        return response()->json(['success' => true, 'data' => $stats]);
    }
}
