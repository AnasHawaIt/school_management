<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Contracts\Services\SettingServiceInterface;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingServiceInterface $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display all settings
     */
    public function index(): JsonResponse
    {
        try {
            $settings = $this->settingService->getAllSettings();

            return response()->json([
                'success' => true,
                'data' => $settings,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified setting
     */
    public function show(string $key): JsonResponse
    {
        try {
            $value = $this->settingService->getSetting($key);

            if ($value === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Setting not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'value' => $value,
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
     * Update settings
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        try {
            $this->settingService->updateMultiple($request->settings);

            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Delete the specified setting
     */
    public function destroy(string $key): JsonResponse
    {
        try {
            $this->settingService->deleteSetting($key);

            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
