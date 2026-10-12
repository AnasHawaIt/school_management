<?php

namespace Modules\School\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\School\Contracts\Services\SemesterServiceInterface;
use Modules\School\Http\Requests\StoreSemesterRequest;
use Modules\School\Http\Requests\UpdateSemesterRequest;
use Modules\School\Http\Resources\SemesterResource;

class SemesterController extends Controller
{
    protected $service;

    public function __construct(SemesterServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->has('academic_year_id')) {
                $semesters = $this->service->getByAcademicYear($request->academic_year_id);
            } else {
                $semesters = $this->service->getAll();
            }

            return response()->json([
                'success' => true,
                'data' => SemesterResource::collection($semesters),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreSemesterRequest $request): JsonResponse
    {
        try {
            $semester = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Semester created successfully',
                'data' => new SemesterResource($semester),
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
            $semester = $this->service->getById($id);

            if (!$semester) {
                return response()->json([
                    'success' => false,
                    'message' => 'Semester not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new SemesterResource($semester),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateSemesterRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Semester updated successfully',
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
                'message' => 'Semester deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function setCurrent(int $id): JsonResponse
    {
        try {
            $this->service->setCurrent($id);

            return response()->json([
                'success' => true,
                'message' => 'Semester set as current successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getCurrent(): JsonResponse
    {
        try {
            $semester = $this->service->getCurrent();

            if (!$semester) {
                return response()->json([
                    'success' => false,
                    'message' => 'No current semester found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new SemesterResource($semester),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
