<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\FinanceReportServiceInterface;

class FinanceReportController extends Controller
{
    public function __construct(protected FinanceReportServiceInterface $service) {}

    public function dashboard(Request $request): JsonResponse
    {
        try {
            $request->validate(['year_id' => 'required|integer|exists:academic_years,id']);
            return response()->json([
                'success' => true,
                'data'    => $this->service->getDashboard($request->year_id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function collection(Request $request): JsonResponse
    {
        try {
            $request->validate(['from' => 'required|date', 'to' => 'required|date|after_or_equal:from']);
            return response()->json([
                'success' => true,
                'data'    => $this->service->getCollectionReport($request->from, $request->to),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function outstanding(Request $request): JsonResponse
    {
        try {
            $request->validate(['year_id' => 'required|integer|exists:academic_years,id']);
            return response()->json([
                'success' => true,
                'data'    => $this->service->getOutstandingReport($request->year_id),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
