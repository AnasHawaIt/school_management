<?php

namespace Modules\Examination\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Examination\Contracts\Services\ExamServiceInterface;
use Modules\Examination\Http\Requests\StoreExamRequest;
use Modules\Examination\Http\Requests\UpdateExamRequest;
use Modules\Examination\Http\Resources\ExamResource;

class ExamController extends Controller
{
    public function __construct(
        protected ExamServiceInterface $examService,
    ) {}

    // GET /exams
    public function index(Request $request): JsonResponse
    {
        $result = $this->examService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => ExamResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /exams
    public function store(StoreExamRequest $request): JsonResponse
    {
        $exam = $this->examService->createExam($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Exam created successfully.',
            'data'    => new ExamResource($exam),
        ], 201);
    }

    // GET /exams/{exam}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new ExamResource($this->examService->getExam($id)),
        ]);
    }

    // PUT /exams/{exam}
    public function update(UpdateExamRequest $request, int $id): JsonResponse
    {
        try {
            $exam = $this->examService->updateExam($id, $request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Exam updated successfully.',
                'data'    => new ExamResource($exam),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // DELETE /exams/{exam}
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->examService->deleteExam($id);
            return response()->json(['success' => true, 'message' => 'Exam deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // POST /exams/{exam}/restore
    public function restore(int $id): JsonResponse
    {
        $this->examService->restoreExam($id);
        return response()->json(['success' => true, 'message' => 'Exam restored successfully.']);
    }

    // PATCH /exams/{exam}/status
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
        ]);
        $exam = $this->examService->updateStatus($id, $request->status);
        return response()->json([
            'success' => true,
            'message' => 'Exam status updated.',
            'data'    => new ExamResource($exam),
        ]);
    }

    // GET /sections/{section}/exams?semester_id=1
    public function sectionExams(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $exams = $this->examService->getSectionExams($sectionId, $request->semester_id);
        return response()->json([
            'success' => true,
            'data'    => ExamResource::collection($exams),
        ]);
    }

    // GET /teachers/{teacher}/exams?semester_id=1
    public function teacherExams(Request $request, int $teacherId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $exams = $this->examService->getTeacherExams($teacherId, $request->semester_id);
        return response()->json([
            'success' => true,
            'data'    => ExamResource::collection($exams),
        ]);
    }
}
