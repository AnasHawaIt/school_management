<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\StudentFeeServiceInterface;
use Modules\Finance\Http\Requests\AssignFeeRequest;
use Modules\Finance\Http\Resources\StudentFeeResource;

class StudentFeeController extends Controller
{
    public function __construct(protected StudentFeeServiceInterface $service) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data'    => StudentFeeResource::collection($this->service->getAll()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(AssignFeeRequest $request): JsonResponse
    {
        try {
            $fee = $this->service->assignFee($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Fee assigned successfully',
                'data'    => new StudentFeeResource($fee),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $fee = $this->service->getById($id);
            if (!$fee) {
                return response()->json(['success' => false, 'message' => 'Student fee not found'], 404);
            }
            return response()->json(['success' => true, 'data' => new StudentFeeResource($fee)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function byStudent(int $studentId, Request $request): JsonResponse
    {
        try {
            $fees = $this->service->getByStudent($studentId, $request->input('year_id'));
            return response()->json([
                'success' => true,
                'data'    => StudentFeeResource::collection($fees),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function assignYearFees(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'student_id' => 'required|integer|exists:students,id',
                'year_id'    => 'required|integer|exists:academic_years,id',
                'grade_id'   => 'required|integer|exists:grades,id',
            ]);
            $fees = $this->service->assignYearFees(
                $request->student_id, $request->year_id, $request->grade_id
            );
            return response()->json([
                'success' => true,
                'message' => count($fees) . ' fee(s) assigned successfully',
                'data'    => StudentFeeResource::collection(collect($fees)),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function overdue(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data'    => StudentFeeResource::collection($this->service->getOverdue()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function waive(Request $request, int $id): JsonResponse
    {
        try {
            $request->validate(['reason' => 'required|string']);
            $this->service->waive($id, $request->reason);
            return response()->json(['success' => true, 'message' => 'Fee waived successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function summary(Request $request): JsonResponse
    {
        try {
            $request->validate(['year_id' => 'required|integer|exists:academic_years,id']);
            $data = $this->service->getSummaryByYear($request->year_id);
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
