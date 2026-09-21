<?php

namespace App\Http\Controllers;

use App\Contracts\Services\CounselorServiceInterface;
use App\Entities\Counselor;
use app\Http\Requests\StoreCounselorRequest;
use app\Http\Requests\UpdateCounselorRequest;
use app\Http\Resources\CounselorResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CounselorController extends Controller
{
    public function __construct(protected CounselorServiceInterface $counselorService) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->counselorService->getAll($request->all());
        return response()->json([
            'success' => true,
            'data'    => CounselorResource::collection($result->items()),
            'meta'    => [
                'current_page' => $result->currentPage(),
                'last_page'    => $result->lastPage(),
                'per_page'     => $result->perPage(),
                'total'        => $result->total(),
            ],
        ]);
    }

    public function store(StoreCounselorRequest $request): JsonResponse
    {
        $counselor = $this->counselorService->createCounselor($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Counselor created successfully.',
            'data'    => new CounselorResource($counselor),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new CounselorResource($this->counselorService->getCounselor($id)),
        ]);
    }

    public function update(UpdateCounselorRequest $request, int $id): JsonResponse
    {
        $counselor = $this->counselorService->updateCounselor($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Counselor updated successfully.',
            'data'    => new CounselorResource($counselor),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->counselorService->deleteCounselor($id);
        return response()->json(['success' => true, 'message' => 'Counselor deleted successfully.']);
    }

    public function restore(int $id): JsonResponse
    {
        $this->counselorService->restoreCounselor($id);
        return response()->json(['success' => true, 'message' => 'Counselor restored successfully.']);
    }

    public function toggleStatus(int $id): JsonResponse
    {
        $counselor = $this->counselorService->toggleStatus($id);
        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'data'    => new CounselorResource($counselor),
        ]);
    }

    public function assignSection(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);
        $this->counselorService->assignSection($id, $data['section_id'], $data['academic_year_id']);
        return response()->json(['success' => true, 'message' => 'Section assigned to counselor.']);
    }

    public function unassignSection(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'section_id'       => 'required|exists:sections,id',
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);
        $this->counselorService->unassignSection($id, $data['section_id'], $data['academic_year_id']);
        return response()->json(['success' => true, 'message' => 'Section unassigned.']);
    }

    public function sections(Request $request): JsonResponse
    {
        $request->validate(['academic_year_id' => 'required|exists:academic_years,id']);
        $counselorId = Counselor::where('user_id', auth()->id())->value('id');

        if (!$counselorId) {
            return response()->json([
                'success' => false,
                'message' => 'sections admins not found for this counselor.',
            ], 404);
        }        $sections = $this->counselorService->getCounselorSections($counselorId, $request->academic_year_id);
        return response()->json(['success' => true, 'data' => $sections]);
    }
}
