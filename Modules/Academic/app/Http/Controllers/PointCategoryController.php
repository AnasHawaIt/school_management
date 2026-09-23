<?php

namespace Modules\Academic\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Academic\app\Entities\PointCategory;

class PointCategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = PointCategory::query();

        if (!empty($request->type)) {
            $query->where('type', $request->type);
        }

        if (!empty($request->is_active)) {
            $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('type')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'           => 'required|string|max:100|unique:point_categories,name',
            'name_ar'        => 'required|string|max:100',
            'type'           => 'required|in:positive,negative',
            'default_points' => 'required|integer|min:1|max:100',
            'icon'           => 'nullable|string|max:50',
            'color'          => 'nullable|string|max:20',
            'auto_assign'    => 'nullable|boolean',
        ]);

        $category = PointCategory::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Category created successfully.',
            'data'    => $category,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = PointCategory::findOrFail($id);

        $data = $request->validate([
            'name'           => 'sometimes|string|max:100|unique:point_categories,name,' . $id,
            'name_ar'        => 'sometimes|string|max:100',
            'type'           => 'sometimes|in:positive,negative',
            'default_points' => 'sometimes|integer|min:1|max:100',
            'icon'           => 'nullable|string|max:50',
            'color'          => 'nullable|string|max:20',
            'is_active'      => 'nullable|boolean',
            'auto_assign'    => 'nullable|boolean',
        ]);

        $category->update($data);
        return response()->json([
            'success' => true,
            'message' => 'Category updated.',
            'data'    => $category->fresh(),
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = PointCategory::findOrFail($id);


        if ($category->studentPoints()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with existing points.',
            ], 422);
        }

        $category->delete();
        return response()->json(['success' => true, 'message' => 'Category deleted.']);
    }
}
