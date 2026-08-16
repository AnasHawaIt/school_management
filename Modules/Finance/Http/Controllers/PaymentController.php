<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\PaymentServiceInterface;
use Modules\Finance\Http\Requests\StorePaymentRequest;
use Modules\Finance\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    public function __construct(protected PaymentServiceInterface $service) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data'    => PaymentResource::collection($this->service->getAll()),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        try {
            $payment = $this->service->recordPayment($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'data'    => new PaymentResource($payment),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $payment = $this->service->getById($id);
            if (!$payment) {
                return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
            }
            return response()->json(['success' => true, 'data' => new PaymentResource($payment)]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function refund(Request $request, int $id): JsonResponse
    {
        try {
            $payment = $this->service->refundPayment($id, $request->input('reason', ''));
            return response()->json([
                'success' => true,
                'message' => 'Payment refunded successfully',
                'data'    => new PaymentResource($payment),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function byStudent(int $studentId): JsonResponse
    {
        try {
            $payments = $this->service->getByStudent($studentId);
            return response()->json([
                'success' => true,
                'data'    => PaymentResource::collection($payments),
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function report(Request $request): JsonResponse
    {
        try {
            $data = $this->service->getReport(
                $request->input('from'),
                $request->input('to')
            );
            return response()->json(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
