<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\AuthController;
use Modules\Core\Http\Controllers\UserController;
use Modules\Core\Http\Controllers\RoleController;
use Modules\Core\Http\Controllers\PermissionController;
use Modules\Core\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes (No authentication required)
Route::post('/login', [AuthController::class, 'login']);

Route::post('users/', [UserController::class, 'store']);
// Protected routes (Authentication required)
Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::prefix('auth')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    // Users Management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
       // Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::post('image/{id}',[UserController::class, 'avatarUpload']);

        // Additional user endpoints
        Route::post('/{id}/restore', [UserController::class, 'restore']);
        Route::post('/{id}/assign-role', [UserController::class, 'assignRole']);
        Route::delete('/{id}/remove-role/{role}', [UserController::class, 'removeRole']);
        Route::get('/{id}/permissions', [UserController::class, 'permissions']);
        Route::post('/{id}/change-password', [UserController::class, 'changePassword']);
    });

    // Roles Management
    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);

        // Role permissions
        Route::post('/{id}/permissions', [RoleController::class, 'attachPermissions']);
        Route::delete('/{id}/permissions/{permissionId}', [RoleController::class, 'detachPermission']);
        Route::put('/{id}/permissions', [RoleController::class, 'syncPermissions']);
    });

    // Permissions
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/grouped', [PermissionController::class, 'grouped']);
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::get('/{key}', [SettingController::class, 'show']);
        Route::post('/', [SettingController::class, 'update']);
        Route::delete('/{key}', [SettingController::class, 'destroy']);
    });
});
