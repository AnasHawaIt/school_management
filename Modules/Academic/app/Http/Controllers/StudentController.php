<?php

namespace App\Http\Controllers;

use App\Contracts\Services\StudentServiceInterface;
use app\Http\Requests\StoreStudentRequest;
use app\Http\Requests\UpdateStudentRequest;
use app\Http\Resources\StudentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StudentController extends Controller
{
    public function __construct(
        protected StudentServiceInterface $studentService,
    ) {}

    // GET /students
    public function index(Request $request): JsonResponse
    {
        $result = $this->studentService->getAllStudents($request->all());
        return response()->json([
            'success' => true,
            'data'    => StudentResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /students
    public function store(StoreStudentRequest $request): JsonResponse
    {
        $student = $this->studentService->createStudent($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Student created successfully.',
            'data'    => new StudentResource($student),
        ], 201);
    }

    // GET /students/{student}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new StudentResource($this->studentService->getStudent($id)),
        ]);
    }

    // PUT /students/{student}
    public function update(UpdateStudentRequest $request, int $id): JsonResponse
    {
        $student = $this->studentService->updateStudent($id, $request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
            'data'    => new StudentResource($student),
        ]);
    }

    // DELETE /students/{student}
    public function destroy(int $id): JsonResponse
    {
        $this->studentService->deleteStudent($id);
        return response()->json(['success' => true, 'message' => 'Student deleted successfully.']);
    }

    // POST /students/{student}/restore
    public function restore(int $id): JsonResponse
    {
        $this->studentService->restoreStudent($id);
        return response()->json(['success' => true, 'message' => 'Student restored successfully.']);
    }

    // POST /students/{student}/transfer
    public function transfer(Request $request, int $id): JsonResponse
    {
        $request->validate(['section_id' => 'required|exists:sections,id']);

        try {
            $this->studentService->transferSection($id, $request->section_id);
            return response()->json(['success' => true, 'message' => 'Student transferred successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // POST /students/promote
    public function promote(Request $request): JsonResponse
    {
        $request->validate([
            'from_section_id' => 'required|exists:sections,id',
            'to_section_id'   => 'required|exists:sections,id|different:from_section_id',
        ]);

        $result = $this->studentService->promoteStudents(
            $request->from_section_id,
            $request->to_section_id
        );

        return response()->json([
            'success' => true,
            'message' => "{$result['promoted_count']} students promoted successfully.",
            'data'    => $result,
        ]);
    }

    // PATCH /students/{student}/status
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:active,inactive,transferred,graduated,expelled',
        ]);
        $student = $this->studentService->toggleStatus($id, $request->status);
        return response()->json([
            'success' => true,
            'message' => 'Student status updated.',
            'data'    => new StudentResource($student),
        ]);
    }

    // GET /students/{student}/parents
    public function parents(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new StudentResource($this->studentService->getStudentWithParents($id)),
        ]);
    }

    // GET /students/{student}/medical-record
    public function medicalRecord(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new StudentResource($this->studentService->getStudentWithMedicalRecord($id)),
        ]);
    }

    // PUT /students/{student}/medical-record
    public function updateMedicalRecord(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'chronic_diseases'  => 'nullable|string',
            'allergies'         => 'nullable|string',
            'medications'       => 'nullable|string',
            'disabilities'      => 'nullable|string',
            'special_needs'     => 'nullable|string',
            'doctor_name'       => 'nullable|string|max:100',
            'doctor_phone'      => 'nullable|string|max:20',
            'insurance_number'  => 'nullable|string|max:50',
            'insurance_company' => 'nullable|string|max:100',
            'notes'             => 'nullable|string',
        ]);
        $record = $this->studentService->updateMedicalRecord($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Medical record updated successfully.',
            'data'    => $record,
        ]);
    }

    // GET /sections/{section}/students
    public function bySection(int $sectionId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => StudentResource::collection($this->studentService->getStudentsBySection($sectionId)),
        ]);
    }

    // GET /sections/{section}/students/stats
    public function sectionStats(int $sectionId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $this->studentService->getSectionStats($sectionId),
        ]);
    }
    public function assignToSection(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'section_id'       => 'required|integer|exists:sections,id',
            'student_id'       => 'required|integer|exists:students,id',
            'semester_id'      => 'required|integer|exists:semesters,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
        ]);

        try {
            $this->studentService->assignStudentToSection(
                $validated['section_id'],
                $validated['student_id'],
                $validated['semester_id'],
                $validated['academic_year_id']
            );

            return response()->json([
                'success' => true,
                'message' => 'Student assigned to section successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
