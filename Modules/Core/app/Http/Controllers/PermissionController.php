<?php

namespace Modules\Core\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Core\app\Contracts\Repositories\PermissionRepositoryInterface;
use Modules\Core\app\Http\Resources\PermissionResource;

class PermissionController extends Controller
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Display a listing of permissions
     */
    public function index(): JsonResponse
    {
        try {
            $permissions = $this->permissionRepository->all();

            return response()->json([
                'success' => true,
                'data' => PermissionResource::collection($permissions),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display permissions grouped by module
     */
    public function grouped(): JsonResponse
    {
        try {
            $permissions = $this->permissionRepository->getAllGrouped();

            return response()->json([
                'success' => true,
                'data' => $permissions,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
