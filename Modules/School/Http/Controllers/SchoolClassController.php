<?php

namespace Modules\School\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\School\Contracts\Services\SchoolClassServiceInterface;
use Modules\School\Http\Requests\StoreSchoolClassRequest;
use Modules\School\Http\Requests\UpdateSchoolClassRequest;
use Modules\School\Http\Resources\SchoolClassResource;

class SchoolClassController extends Controller
{
    protected $service;

    public function __construct(SchoolClassServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            if ($request->has('grade_id')) {
                $classes = $this->service->getByGrade($request->grade_id);
            } else {
                $classes = $this->service->getAll();
            }

            return response()->json([
                'success' => true,
                'data' => SchoolClassResource::collection($classes),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreSchoolClassRequest $request): JsonResponse
    {
        try {
            $class = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class created successfully',
                'data' => new SchoolClassResource($class),
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
            $class = $this->service->getById($id);

            if (!$class) {
                return response()->json([
                    'success' => false,
                    'message' => 'Class not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new SchoolClassResource($class),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateSchoolClassRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Class updated successfully',
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
                'message' => 'Class deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
