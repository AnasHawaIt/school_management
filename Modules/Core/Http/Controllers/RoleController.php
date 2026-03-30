<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Contracts\Services\RoleServiceInterface;
use Modules\Core\Http\Requests\StoreRoleRequest;
use Modules\Core\Http\Requests\UpdateRoleRequest;
use Modules\Core\Http\Resources\RoleResource;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleServiceInterface $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of roles
     */
    public function index(): JsonResponse
    {
        try {
            $roles = $this->roleService->getAllRoles();

            return response()->json([
                'success' => true,
                'data' => RoleResource::collection($roles),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created role
     */
    public function store(StoreRoleRequest $request): JsonResponse
    {
        try {
            $role = $this->roleService->createRole($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'data' => new RoleResource($role),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified role
     */
    public function show(int $id): JsonResponse
    {
        try {
            $role = $this->roleService->getRole($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new RoleResource($role),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified role
     */
    public function update(UpdateRoleRequest $request, int $id): JsonResponse
    {
        try {
            $this->roleService->updateRole($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified role
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->roleService->deleteRole($id);

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Attach permissions to role
     */
    public function attachPermissions(Request $request, int $roleId): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $this->roleService->attachPermissions($roleId, $request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions attached successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Detach permission from role
     */
    public function detachPermission(int $roleId, int $permissionId): JsonResponse
    {
        try {
            $this->roleService->detachPermission($roleId, $permissionId);

            return response()->json([
                'success' => true,
                'message' => 'Permission detached successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Sync permissions with role
     */
    public function syncPermissions(Request $request, int $roleId): JsonResponse
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        try {
            $this->roleService->syncPermissions($roleId, $request->permissions);

            return response()->json([
                'success' => true,
                'message' => 'Permissions synced successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
