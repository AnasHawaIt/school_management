<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Contracts\Services\TeacherServiceInterface;
use Modules\Academic\Http\Requests\StoreTeacherRequest;
use Modules\Academic\Http\Requests\UpdateTeacherRequest;
use Modules\Academic\Http\Resources\TeacherResource;

class TeacherController extends Controller
{
    public function __construct(
        protected TeacherServiceInterface $teacherService,
    ) {}

    // GET /teachers
    public function index(Request $request): JsonResponse
    {
        $result = $this->teacherService->getAllTeachers($request->all());
        return response()->json([
            'success' => true,
            'data'    => TeacherResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /teachers
    public function store(StoreTeacherRequest $request): JsonResponse
    {
        $teacher = $this->teacherService->createTeacher($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully.',
            'data'    => new TeacherResource($teacher),
        ], 201);
    }

    // GET /teachers/{teacher}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new TeacherResource($this->teacherService->getTeacher($id)),
        ]);
    }

    // PUT /teachers/{teacher}
    public function update(UpdateTeacherRequest $request, int $id): JsonResponse
    {
        $teacher = $this->teacherService->updateTeacher($id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully.',
            'data'    => new TeacherResource($teacher),
        ]);
    }

    // DELETE /teachers/{teacher}
    public function destroy(int $id): JsonResponse
    {
        $this->teacherService->deleteTeacher($id);
        return response()->json(['success' => true, 'message' => 'Teacher deleted successfully.']);
    }

    // POST /teachers/{teacher}/restore
    public function restore(int $id): JsonResponse
    {
        $this->teacherService->restoreTeacher($id);
        return response()->json(['success' => true, 'message' => 'Teacher restored successfully.']);
    }

    // PATCH /teachers/{teacher}/toggle-status
    public function toggleStatus(int $id): JsonResponse
    {
        $teacher = $this->teacherService->toggleStatus($id);
        return response()->json([
            'success' => true,
            'message' => 'Teacher status updated.',
            'data'    => new TeacherResource($teacher),
        ]);
    }

    // GET /teachers/{teacher}/qualifications
    public function qualifications(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new TeacherResource($this->teacherService->getTeacherWithQualifications($id)),
        ]);
    }

    // POST /teachers/{teacher}/qualifications
    public function addQualification(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'type'           => 'required|in:degree,certificate,training,award',
            'title'          => 'required|string|max:200',
            'institution'    => 'required|string|max:200',
            'field_of_study' => 'nullable|string|max:150',
            'year_obtained'  => 'required|digits:4|integer',
            'expiry_date'    => 'nullable|date',
            'description'    => 'nullable|string',
        ]);

        $qualification = $this->teacherService->addQualification($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Qualification added successfully.',
            'data'    => $qualification,
        ], 201);
    }

    // DELETE /teachers/qualifications/{qualification}
    public function deleteQualification(int $qualificationId): JsonResponse
    {
        $this->teacherService->deleteQualification($qualificationId);
        return response()->json(['success' => true, 'message' => 'Qualification deleted successfully.']);
    }

    // GET /teachers/{teacher}/timetable?semester_id=1
    public function timetable(Request $request, int $id): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        return response()->json([
            'success' => true,
            'data'    => new TeacherResource($this->teacherService->getTeacherTimetable($id, $request->semester_id)),
        ]);
    }
}
