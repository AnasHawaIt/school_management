<?php

namespace Modules\Attendance\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Attendance\app\Contracts\Services\LeaveRequestServiceInterface;
use Modules\Attendance\app\Http\Requests\StoreLeaveRequestRequest;
use Modules\Attendance\app\Http\Resources\LeaveRequestResource;

class LeaveRequestController extends Controller
{
    public function __construct(
        protected LeaveRequestServiceInterface $leaveService,
    ) {}

    // GET /leave-requests
    public function index(Request $request): JsonResponse
    {
        $result = $this->leaveService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => LeaveRequestResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // GET /leave-requests/pending
    public function pending(): JsonResponse
    {
        $requests = $this->leaveService->getPending();
        return response()->json([
            'success' => true,
            'data'    => LeaveRequestResource::collection($requests),
        ]);
    }

    // POST /leave-requests
    public function store(StoreLeaveRequestRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['status'] = 'pending';
        // رفع المرفق إن وجد
        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store('leave-requests', 'public');
        }

        try {
            $leaveRequest = $this->leaveService->createRequest($data);
            return response()->json([
                'success' => true,
                'message' => 'Leave request submitted successfully.',
                'data'    => new LeaveRequestResource($leaveRequest),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // GET /leave-requests/{id}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new LeaveRequestResource($this->leaveService->findById($id)),
        ]);
    }

    // PUT /leave-requests/{id}
    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'type'      => 'sometimes|in:sick,personal,emergency,other',
            'from_date' => 'sometimes|date',
            'to_date'   => 'sometimes|date|after_or_equal:from_date',
            'reason'    => 'sometimes|string|max:1000',
        ]);

        try {
            $leaveRequest = $this->leaveService->updateRequest($id, $data);
            return response()->json([
                'success' => true,
                'message' => 'Leave request updated successfully.',
                'data'    => new LeaveRequestResource($leaveRequest),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // DELETE /leave-requests/{id}
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->leaveService->deleteRequest($id);
            return response()->json(['success' => true, 'message' => 'Leave request deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // POST /leave-requests/{id}/approve
    public function approve(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['notes' => 'nullable|string|max:500']);
        try {
            $leaveRequest = $this->leaveService->approve($id, Auth::id(), $data['notes'] ?? null);
            return response()->json([
                'success' => true,
                'message' => 'Leave request approved.',
                'data'    => new LeaveRequestResource($leaveRequest),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    // POST /leave-requests/{id}/reject
    public function reject(Request $request, int $id): JsonResponse
    {
        $data = $request->validate(['notes' => 'nullable|string|max:500']);
        try {
            $leaveRequest = $this->leaveService->reject($id, Auth::id(), $data['notes'] ?? null);
            return response()->json([
                'success' => true,
                'message' => 'Leave request rejected.',
                'data'    => new LeaveRequestResource($leaveRequest),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }
}
