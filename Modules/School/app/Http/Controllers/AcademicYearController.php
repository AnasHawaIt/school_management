<?php

namespace Modules\School\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\School\Contracts\Services\AcademicYearServiceInterface;
use Modules\School\Http\Requests\StoreAcademicYearRequest;
use Modules\School\Http\Requests\UpdateAcademicYearRequest;
use Modules\School\Http\Resources\AcademicYearResource;

class AcademicYearController extends Controller
{
    protected $service;

    public function __construct(AcademicYearServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $years = $this->service->getAll();

            return response()->json([
                'success' => true,
                'data' => AcademicYearResource::collection($years),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreAcademicYearRequest $request): JsonResponse
    {
        try {
            $year = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Academic year created successfully',
                'data' => new AcademicYearResource($year),
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
            $year = $this->service->getById($id);

            if (!$year) {
                return response()->json([
                    'success' => false,
                    'message' => 'Academic year not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new AcademicYearResource($year),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateAcademicYearRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Academic year updated successfully',
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
                'message' => 'Academic year deleted successfully',
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
                'message' => 'Academic year set as current successfully',
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
            $year = $this->service->getCurrent();

            if (!$year) {
                return response()->json([
                    'success' => false,
                    'message' => 'No current academic year found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new AcademicYearResource($year),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
