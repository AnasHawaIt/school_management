<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\FeeTypeServiceInterface;
use Modules\Finance\Http\Resources\FeeTypeResource;

class FeeTypeController extends Controller
{
    public function __construct(protected FeeTypeServiceInterface $service) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'data' => FeeTypeResource::collection($this->service->getAll())]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) return response()->json(['success' => false, 'message' => 'FeeType not found'], 404);
            return response()->json(['success' => true, 'data' => new FeeTypeResource($item)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $item = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'FeeType created successfully', 'data' => new FeeTypeResource($item)], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function update(\Illuminate\Http\Request $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'FeeType updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'FeeType deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
