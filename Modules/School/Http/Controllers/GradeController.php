<?php

namespace Modules\School\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\School\Contracts\Services\GradeServiceInterface;
use Modules\School\Http\Requests\StoreGradeRequest;
use Modules\School\Http\Requests\UpdateGradeRequest;
use Modules\School\Http\Resources\GradeResource;

class GradeController extends Controller
{
    protected $service;

    public function __construct(GradeServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->has('level')) {
                $grades = $this->service->getByLevel($request->level);
            } else {
                $grades = $this->service->getAll();
            }

            return response()->json([
                'success' => true,
                'data' => GradeResource::collection($grades),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreGradeRequest $request): JsonResponse
    {
        try {
            $grade = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Grade created successfully',
                'data' => new GradeResource($grade),
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
            $grade = $this->service->getById($id);

            if (!$grade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Grade not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new GradeResource($grade),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateGradeRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Grade updated successfully',
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
                'message' => 'Grade deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
