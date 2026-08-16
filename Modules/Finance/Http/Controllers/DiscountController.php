<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\DiscountServiceInterface;
use Modules\Finance\Http\Resources\DiscountResource;

class DiscountController extends Controller
{
    public function __construct(protected DiscountServiceInterface $service) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json(['success' => true, 'data' => DiscountResource::collection($this->service->getAll())]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = $this->service->getById($id);
            if (!$item) return response()->json(['success' => false, 'message' => 'Discount not found'], 404);
            return response()->json(['success' => true, 'data' => new DiscountResource($item)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $item = $this->service->create($request->validated());
            return response()->json(['success' => true, 'message' => 'Discount created successfully', 'data' => new DiscountResource($item)], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function update(\Illuminate\Http\Request $request, int $id): JsonResponse
    {
        try {
            $this->service->update($id, $request->validated());
            return response()->json(['success' => true, 'message' => 'Discount updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json(['success' => true, 'message' => 'Discount deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
