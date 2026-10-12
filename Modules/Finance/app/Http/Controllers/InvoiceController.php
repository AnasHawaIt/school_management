<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Finance\Contracts\Services\InvoiceServiceInterface;
use Modules\Finance\Http\Requests\GenerateInvoiceRequest;
use Modules\Finance\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceServiceInterface $service) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($this->service->getAll()),
        ]);
    }

    public function generate(GenerateInvoiceRequest $request): JsonResponse
    {
        try {
            $invoice = $this->service->generateForStudent(
                $request->integer('student_id'),
                $request->integer('year_id'),
                $request->filled('due_days') ? $request->integer('due_days') : null,
            );

            return response()->json([
                'success' => true,
                'message' => 'Invoice generated successfully.',
                'data' => new InvoiceResource($invoice),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e instanceof \DomainException ? 422 : 500);
        }
    }

    public function overdue(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($this->service->getOverdue()),
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $invoice = $this->service->getById($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new InvoiceResource($invoice),
        ]);
    }

    public function send(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Invoice sent successfully.',
                'data' => new InvoiceResource($this->service->send($id)),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e instanceof \DomainException ? 422 : 500);
        }
    }

    public function cancel(int $id): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Invoice cancelled successfully.',
                'data' => new InvoiceResource($this->service->cancel($id)),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e instanceof \DomainException ? 422 : 500);
        }
    }

    public function byStudent(int $studentId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => InvoiceResource::collection($this->service->getByStudent($studentId)),
        ]);
    }
}
