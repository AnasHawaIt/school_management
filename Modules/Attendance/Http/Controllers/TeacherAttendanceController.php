<?php

namespace Modules\Attendance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Contracts\Services\TeacherAttendanceServiceInterface;
use Modules\Attendance\Http\Requests\RecordTeacherAttendanceRequest;
use Modules\Attendance\Http\Resources\TeacherAttendanceResource;

class TeacherAttendanceController extends Controller
{
    public function __construct(
        protected TeacherAttendanceServiceInterface $attendanceService,
    ) {}

    // GET /teacher-attendance
    public function index(Request $request): JsonResponse
    {
        $result = $this->attendanceService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => TeacherAttendanceResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // GET /teacher-attendance/daily?date=2024-01-01
    public function daily(Request $request): JsonResponse
    {
        $request->validate(['date' => 'required|date']);
        $records = $this->attendanceService->getDailyAttendance($request->date);
        return response()->json([
            'success' => true,
            'data'    => TeacherAttendanceResource::collection($records),
        ]);
    }

    // POST /teacher-attendance
    public function store(RecordTeacherAttendanceRequest $request): JsonResponse
    {
        $record = $this->attendanceService->recordAttendance($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Teacher attendance recorded successfully.',
            'data'    => new TeacherAttendanceResource($record),
        ], 201);
    }

    // GET /teacher-attendance/{id}
    public function show(int $id): JsonResponse
    {
        $record = $this->attendanceService->getAll(['id' => $id]);
        return response()->json([
            'success' => true,
            'data'    => new TeacherAttendanceResource($record),
        ]);
    }

    // PUT /teacher-attendance/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status_id'      => 'sometimes|exists:attendance_statuses,id',
            'check_in_time'  => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'late_minutes'   => 'nullable|integer|min:0|max:120',
            'notes'          => 'nullable|string|max:500',
        ]);
        $record = $this->attendanceService->updateAttendance($id, $data);
        return response()->json([
            'success' => true,
            'message' => 'Teacher attendance updated successfully.',
            'data'    => new TeacherAttendanceResource($record),
        ]);
    }

    // DELETE /teacher-attendance/{id}
    public function destroy(int $id): JsonResponse
    {
        $this->attendanceService->deleteAttendance($id);
        return response()->json(['success' => true, 'message' => 'Teacher attendance deleted successfully.']);
    }

    // GET /teachers/{teacher}/attendance-report
    public function teacherReport(Request $request, int $teacherId): JsonResponse
    {
        $result = $this->attendanceService->getTeacherReport($teacherId, $request->all());
        return response()->json([
            'success' => true,
            'data'    => TeacherAttendanceResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // GET /teachers/{teacher}/attendance-stats
    public function teacherStats(Request $request, int $teacherId): JsonResponse
    {
        $stats = $this->attendanceService->getTeacherStats($teacherId, $request->all());
        return response()->json(['success' => true, 'data' => $stats]);
    }
}
