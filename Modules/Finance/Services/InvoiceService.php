<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\InvoiceRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\InvoiceServiceInterface;
use Modules\Finance\Entities\Invoice;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface    $invoiceRepo,
        protected StudentFeeRepositoryInterface $studentFeeRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->invoiceRepo->all();
    }

    public function getById(int $id): ?Invoice
    {
        return $this->invoiceRepo->find($id);
    }

    public function generateForStudent(int $studentId, int $yearId): Invoice
    {
        DB::beginTransaction();
        try {
            $fees = $this->studentFeeRepo->getByStudent($studentId, $yearId)
                ->whereIn('status', ['unpaid', 'partial', 'overdue']);

            if ($fees->isEmpty()) {
                throw new \Exception('No pending fees found for this student');
            }

            $total     = $fees->sum('net_amount');
            $discount  = $fees->sum('discount_amount');
            $net       = $fees->sum('net_amount');
            $paid      = $fees->sum('paid_amount');
            $remaining = $fees->sum('remaining_amount');

            $invoice = $this->invoiceRepo->create([
                'student_id'       => $studentId,
                'academic_year_id' => $yearId,
                'issued_by'        => Auth::id(),
                'total_amount'     => $total,
                'discount_amount'  => $discount,
                'net_amount'       => $net,
                'paid_amount'      => $paid,
                'remaining_amount' => $remaining,
                'status'           => 'draft',
                'issue_date'       => now()->toDateString(),
                'due_date'         => now()->addDays(30)->toDateString(),
            ]);

            DB::commit();
            return $invoice->load(['student', 'academicYear', 'issuedBy']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function send(int $id): Invoice
    {
        $invoice = $this->invoiceRepo->findOrFail($id);
        $this->invoiceRepo->update($id, ['status' => 'sent']);
        return $invoice;
    }

    public function cancel(int $id): bool
    {
        return $this->invoiceRepo->update($id, ['status' => 'cancelled']);
    }

    public function getOverdue(): Collection
    {
        return $this->invoiceRepo->getModel()->overdue()
            ->with(['student', 'academicYear'])->get();
    }

    public function getByStudent(int $studentId): Collection
    {
        return $this->invoiceRepo->getModel()
            ->where('student_id', $studentId)
            ->with(['academicYear', 'issuedBy'])
            ->orderByDesc('issue_date')
            ->get();
    }
}
