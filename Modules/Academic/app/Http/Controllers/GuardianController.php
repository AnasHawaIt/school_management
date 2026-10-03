<?php

namespace Modules\Academic\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\Contracts\Services\GuardianServiceInterface;
use Modules\Academic\Http\Requests\StoreGuardianRequest;
use Modules\Academic\Http\Resources\GuardianResource;

class GuardianController extends Controller
{
    public function __construct(
        protected GuardianServiceInterface $guardianService,
    ) {}

    // GET /guardians
    public function index(Request $request): JsonResponse
    {
        $result = $this->guardianService->getAllGuardians($request->all());
        return response()->json(
            [
            'success' => true,
            'data'    => GuardianResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    // POST /guardians
    public function store(StoreGuardianRequest $request): JsonResponse
    {
        $guardian = $this->guardianService->createGuardian($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Guardian created successfully.',
            'data'    => new GuardianResource($guardian),
        ], 201);
    }

    // GET /guardians/{guardian}
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new GuardianResource($this->guardianService->getGuardian($id)),
        ]);
    }

    // PUT /guardians/{guardian}
    public function update(Request $request, int $id): JsonResponse
    {
        $guardian = $this->guardianService->updateGuardian($id, $request->all());
        return response()->json([
            'success' => true,
            'message' => 'Guardian updated successfully.',
            'data'    => new GuardianResource($guardian),
        ]);
    }

    // DELETE /guardians/{guardian}
    public function destroy(int $id): JsonResponse
    {
        $this->guardianService->deleteGuardian($id);
        return response()->json(['success' => true, 'message' => 'Guardian deleted successfully.']);
    }

    // POST /guardians/{guardian}/restore
    public function restore(int $id): JsonResponse
    {
        $result = $this->guardianService->restoreGuardian($id);

        if ($result === false) {
            return response()->json([
                'success' => false,
                'message' => 'this parent is exist.',
            ], 400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Guardian restored successfully.'
        ]);

    }

    // GET /guardians/{guardian}/students
    public function students(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new GuardianResource($this->guardianService->getGuardianWithStudents($id)),
        ]);
    }

    // POST /guardians/{guardian}/attach-student
    public function attachStudent(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'student_id'         => 'required|exists:students,id',
            'relationship'       => 'required|in:father,mother,guardian,other',
            'is_primary_contact' => 'nullable|boolean',
            'can_pickup'         => 'nullable|boolean',
        ]);

        $this->guardianService->attachStudent($id, $data['student_id'], [
            'relationship'       => $data['relationship'],
            'is_primary_contact' => $data['is_primary_contact'] ?? false,
            'can_pickup'         => $data['can_pickup'] ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Student linked to guardian successfully.']);
    }

    // DELETE /guardians/{guardian}/detach-student/{student}
    public function detachStudent(int $guardianId, int $studentId): JsonResponse
    {
        $this->guardianService->detachStudent($guardianId, $studentId);
        return response()->json(['success' => true, 'message' => 'Student unlinked from guardian successfully.']);
    }
}
