<?php

namespace Modules\Core\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Core\app\Contracts\Repositories\ActivityLogRepositoryInterface;
use Modules\Core\app\Contracts\Repositories\UserRepositoryInterface;
use Modules\Core\app\Contracts\Services\UserServiceInterface;
use Modules\Core\app\Entities\User;
use Modules\Core\app\Http\Requests\StoreUserRequest;
use Modules\Core\app\Http\Requests\UpdateUserRequest;
use Modules\Core\app\Http\Resources\UserResource;

class UserController extends Controller
{
    protected $userService;
    protected $userRepository;
    protected $activityLogRepository;

    public function __construct(
        UserServiceInterface $userService,
        UserRepositoryInterface $userRepository,
        ActivityLogRepositoryInterface $activityLogRepository
    ) {
        $this->userService = $userService;
        $this->userRepository = $userRepository; // هذا كان مفقوداً
        $this->activityLogRepository = $activityLogRepository; // هذا كان مفقوداً
    }

    /**
     * Display a listing of users
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['user_type', 'is_active', 'search', 'per_page']);
            $users = $this->userService->getAllUsers($filters);

            return response()->json([
                'success' => true,
                'data' => UserResource::collection($users),
                'meta' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created user
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => new UserResource($user),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display the specified user
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUser($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new UserResource($user),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $this->userService->updateUser($id, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove the specified user
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->userService->deleteUser($id);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Restore a soft deleted user
     */
    public function restore(int $id): JsonResponse
    {
        try {
            $this->userService->restoreUser($id);

            return response()->json([
                'success' => true,
                'message' => 'User restored successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        try {
            $this->userService->assignRole($id, $request->role);

            return response()->json([
                'success' => true,
                'message' => 'Role assigned successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(int $userId, string $role): JsonResponse
    {
        try {
            $this->userService->removeRole($userId, $role);

            return response()->json([
                'success' => true,
                'message' => 'Role removed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get user permissions
     */
    public function permissions(int $id): JsonResponse
    {
        try {
            $permissions = $this->userService->getUserPermissions($id);

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

    /**
     * Change user password
     */
    public function changePassword(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        try {
            $this->userService->changePassword($id, $request->new_password);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function avatarUpload(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $user = $this->userRepository->findOrFail($id);
        $oldValues = $user->toArray();
        $data=[];
        if ($request->hasFile('avatar')) {
            // قم بحذف الصورة القديمة من السيرفر إذا لزم الأمر
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }

            $data['avatar']=$request->file('avatar')->store('avatars', 'public');
        }
        $updated = $this->userRepository->update($id, $data);
        $this->activityLogRepository->log([
            'action' => 'update',
            'model_type' => User::class,
            //  'model_id' => $id,
            'old_values' => $oldValues,
            'new_values' => $data,
        ]);
        DB::commit();

        return response()->json([
            'message' => 'Avatar updated successfully',
            'data' => $data
        ]);
    }
}
