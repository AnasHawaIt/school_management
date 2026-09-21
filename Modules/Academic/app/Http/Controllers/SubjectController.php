<?php

namespace App\Http\Controllers;

use App\Contracts\Services\SubjectServiceInterface;
use app\Http\Requests\StoreSubjectRequest;
use app\Http\Resources\SubjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SubjectController extends Controller
{
    public function __construct(
        protected SubjectServiceInterface $subjectService,
    ) {}

    // GET /subjects
    public function index(Request $request): JsonResponse
    {
        $result = $this->subjectService->getAllSubjects($request->all());
        return response()->json([
            'success' => true,
            'data'    => SubjectResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /subjects
    public function store(StoreSubjectRequest $request): JsonResponse
    {
        $subject = $this->subjectService->createSubject($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Subject created successfully.',
            'data'    => new SubjectResource($subject),
        ], 201);
    }

    // GET /subjects/{subject}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new SubjectResource($this->subjectService->getSubject($id)),
        ]);
    }

    // PUT /subjects/{subject}
    public function update(Request $request, int $id): JsonResponse
    {
        $subject = $this->subjectService->updateSubject($id, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'Subject updated successfully.',
            'data'    => new SubjectResource($subject),
        ]);
    }

    // DELETE /subjects/{subject}
    public function destroy(int $id): JsonResponse
    {
        $this->subjectService->deleteSubject($id);
        return response()->json(['success' => true, 'message' => 'Subject deleted successfully.']);
    }

    // POST /subjects/{subject}/restore
    public function restore(int $id): JsonResponse
    {
        $this->subjectService->restoreSubject($id);
        return response()->json(['success' => true, 'message' => 'Subject restored successfully.']);
    }

    // PATCH /subjects/{subject}/toggle-status
    public function toggleStatus(int $id): JsonResponse
    {
        $subject = $this->subjectService->toggleStatus($id);
        return response()->json([
            'success' => true,
            'message' => 'Subject status updated.',
            'data'    => new SubjectResource($subject),
        ]);
    }

    // GET /grades/{grade}/subjects
    public function byGrade(int $gradeId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => SubjectResource::collection($this->subjectService->getSubjectsByGrade($gradeId)),
        ]);
    }

    // GET /subjects/{subject}/teachers
    public function teachers(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new SubjectResource($this->subjectService->getSubjectWithTeachers($id)),
        ]);
    }

    // POST /subjects/{subject}/assign-teacher
    public function assignTeacher(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'teacher_id'       => 'required|exists:teachers,id',
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $this->subjectService->assignTeacher($id, $data['teacher_id'], $data['section_id'], $data['academic_year_id']);
        return response()->json(['success' => true, 'message' => 'Teacher assigned to subject successfully.']);
    }

    // DELETE /subjects/{subject}/unassign-teacher
    public function unassignTeacher(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'teacher_id'       => 'required|exists:teachers,id',
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $this->subjectService->unassignTeacher($id, $data['teacher_id'], $data['section_id'], $data['academic_year_id']);
        return response()->json(['success' => true, 'message' => 'Teacher unassigned from subject successfully.']);
    }
}
