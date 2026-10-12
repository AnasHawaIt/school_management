<?php

namespace Modules\School\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\School\Contracts\Services\SectionServiceInterface;
use Modules\School\Entities\Section;
use Modules\School\Http\Requests\StoreSectionRequest;
use Modules\School\Http\Requests\UpdateSectionRequest;
use Modules\School\Http\Resources\SectionResource;

class SectionController extends Controller
{
    protected $service;

    public function __construct(SectionServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->has('class_id')) {
                $sections = $this->service->getByClass($request->class_id);
            } else {
                $sections = $this->service->getAll();
            }

            return response()->json([
                'success' => true,
                'data' => SectionResource::collection($sections),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreSectionRequest $request): JsonResponse
    {
        try {
            $section = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Section created successfully',
                'data' => new SectionResource($section),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $section = $this->service->getById($id);

            if (!$section) {
                return response()->json([
                    'success' => false,
                    'message' => 'Section not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new SectionResource($section),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateSectionRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getSectionByTeacher(Request $request): JsonResponse
    {
        try {

            $classId = $request->input('class_id') ?? $request->input('classId');

            if (!$classId) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Class ID is required.',
                ], 422);
            }

            $teacherId = Auth::id();

            $sections = Section::where('teacher_id', $teacherId)
                ->where('class_id', $classId)
                ->select('id', 'name')
                ->get();

            if ($sections->isEmpty()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No sections found for this teacher in the selected class.',
                    'data'    => []
                ], 404);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Sections retrieved successfully.',
                'data'    => $sections
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'An error occurred while fetching sections.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
