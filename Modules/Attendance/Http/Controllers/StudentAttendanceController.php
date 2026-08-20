<?php

namespace Modules\Attendance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Services\StudentPointService;
use Modules\Attendance\Contracts\Services\StudentAttendanceServiceInterface;
use Modules\Attendance\Http\Requests\RecordStudentAttendanceRequest;
use Modules\Attendance\Http\Requests\BulkRecordAttendanceRequest;
use Modules\Attendance\Http\Resources\StudentAttendanceResource;

class StudentAttendanceController extends Controller
{
    public function __construct(
        protected StudentAttendanceServiceInterface $attendanceService,protected StudentPointService $pointService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->attendanceService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => StudentAttendanceResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }


    public function sectionAttendance(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['date' => 'required|date']);
        $records = $this->attendanceService->getSectionAttendance($sectionId, $request->date);
        return response()->json([
            'success' => true,
            'data'    => StudentAttendanceResource::collection($records),
        ]);
    }


    public function store(RecordStudentAttendanceRequest $request): JsonResponse
    {
        $record = $this->attendanceService->recordAttendance($request->validated());
        $this->pointService->autoAssignFromAttendance($record);
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded successfully.',
            'data'    => new StudentAttendanceResource($record),
        ], 201);
    }


    public function bulk(BulkRecordAttendanceRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->attendanceService->bulkRecord(
            $data['section_id'],
            $data['date'],
            collect($data['records'])->map(fn($r) => array_merge($r, [
                'academic_year_id' => $data['academic_year_id'],
                'semester_id'      => $data['semester_id'],
            ]))->toArray()
        );
        return response()->json([
            'success' => true,
            'message' => 'Attendance recorded for ' . count($data['records']) . ' students.',
        ]);
    }


    public function show(int $id): JsonResponse
    {
        $attendances = $this->attendanceService->getAll(['student_id' => $id]);
        return response()->json([
            'success' => true,
            'data'    => StudentAttendanceResource::collection($attendances),
        ]);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status_id'     => 'sometimes|exists:attendance_statuses,id',
            'check_in_time' => 'nullable|date_format:H:i',
            'late_minutes'  => 'nullable|integer|min:0|max:120',
            'notes'         => 'nullable|string|max:500',
        ]);
        $record = $this->attendanceService->updateAttendance($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Attendance updated successfully.',
            'data'    => new StudentAttendanceResource($record),
        ]);
    }


    public function destroy(int $id): JsonResponse
    {
        $this->attendanceService->deleteAttendance($id);
        return response()->json(['success' => true, 'message' => 'Attendance deleted successfully.']);
    }


    public function studentReport(Request $request, int $studentId): JsonResponse
    {
        $result = $this->attendanceService->getStudentReport($studentId, $request->all());
        return response()->json([
            'success' => true,
            'data'    => StudentAttendanceResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'total'        => $result->total(),
            ],
        ]);
    }


    public function studentStats(Request $request, int $studentId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $stats = $this->attendanceService->getStudentStats($studentId, $request->semester_id);
        return response()->json(['success' => true, 'data' => $stats]);
    }


    public function sectionStats(Request $request, int $sectionId): JsonResponse
    {
        $request->validate(['semester_id' => 'required|exists:semesters,id']);
        $stats = $this->attendanceService->getSectionStats($sectionId, $request->semester_id);
        return response()->json(['success' => true, 'data' => $stats]);
    }
}
