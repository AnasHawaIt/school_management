<?php

namespace Modules\Transport\app\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Services\BusTrackingService;

class BusTrackingController extends Controller
{
    public function __construct(
        protected BusTrackingService $trackingService
    ) {}

    public function currentStatus(Bus $bus): JsonResponse
    {
        $data = $this->trackingService->getCurrentStatus($bus);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
