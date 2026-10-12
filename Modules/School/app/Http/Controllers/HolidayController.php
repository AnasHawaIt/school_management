<?php

namespace Modules\School\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\School\Contracts\Services\HolidayServiceInterface;
use Modules\School\Http\Requests\StoreHolidayRequest;
use Modules\School\Http\Requests\UpdateHolidayRequest;
use Modules\School\Http\Resources\HolidayResource;

class HolidayController extends Controller
{
    protected $service;

    public function __construct(HolidayServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $holidays = $this->service->getAll();

            return response()->json([
                'success' => true,
                'data' => HolidayResource::collection($holidays),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreHolidayRequest $request): JsonResponse
    {
        try {
            $holiday = $this->service->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Holiday created successfully',
                'data' => new HolidayResource($holiday),
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
            $holiday = $this->service->getById($id);

            if (!$holiday) {
                return response()->json([
                    'success' => false,
                    'message' => 'Holiday not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new HolidayResource($holiday),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateHolidayRequest $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Holiday updated successfully',
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
                'message' => 'Holiday deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getUpcoming(): JsonResponse
    {
        try {
            $holidays = $this->service->getUpcoming();

            return response()->json([
                'success' => true,
                'data' => HolidayResource::collection($holidays),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
