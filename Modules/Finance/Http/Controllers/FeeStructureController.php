<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\FeeStructureServiceInterface;
use Modules\Finance\Http\Resources\FeeStructureResource;

class FeeStructureController extends Controller
{
    public function __construct(protected FeeStructureServiceInterface $service) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'data' => FeeStructureResource::collection($this->service->getAll())]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) return response()->json(['success' => false, 'message' => 'FeeStructure not found'], 404);
            return response()->json(['success' => true, 'data' => new FeeStructureResource($item)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $item = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'FeeStructure created successfully', 'data' => new FeeStructureResource($item)], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function update(\Illuminate\Http\Request $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'FeeStructure updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'FeeStructure deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
