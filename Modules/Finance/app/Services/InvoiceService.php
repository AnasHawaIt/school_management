<?php

namespace Modules\Finance\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Contracts\Repositories\InvoiceRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\InvoiceServiceInterface;
use Modules\Finance\Entities\Invoice;
use Modules\Finance\Entities\InvoiceItem;

class InvoiceService implements InvoiceServiceInterface
{
    public function __construct(
        protected InvoiceRepositoryInterface $invoiceRepo,
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

    public function generateForStudent(int $studentId, int $yearId, ?int $dueDays = null): Invoice
    {
        return DB::transaction(function () use ($studentId, $yearId, $dueDays) {
            $fees = $this->studentFeeRepo->getByStudent($studentId, $yearId)
                ->filter(fn ($fee) => in_array($fee->status, ['unpaid', 'partial', 'overdue'], true))
                ->values();

            if ($fees->isEmpty()) {
                throw new \DomainException('No pending fees found for this student.');
            }

            $alreadyInvoicedFeeIds = InvoiceItem::query()
                ->whereIn('student_fee_id', $fees->pluck('id'))
                ->whereHas('invoice', fn ($q) => $q->where('status', '!=', 'cancelled'))
                ->pluck('student_fee_id');

            $fees = $fees->reject(fn ($fee) => $alreadyInvoicedFeeIds->contains($fee->id))->values();

            if ($fees->isEmpty()) {
                throw new \DomainException('All pending fees are already included in an active invoice.');
            }

            $total = (float) $fees->sum('original_amount');
            $discount = (float) $fees->sum('discount_amount');
            $net = (float) $fees->sum('net_amount');
            $paid = (float) $fees->sum('paid_amount');
            $remaining = max(0, $net - $paid);

            $invoice = $this->invoiceRepo->create([
                'student_id' => $studentId,
                'academic_year_id' => $yearId,
                'issued_by' => Auth::id(),
                'total_amount' => $total,
                'discount_amount' => $discount,
                'net_amount' => $net,
                'paid_amount' => $paid,
                'remaining_amount' => $remaining,
                'status' => 'draft',
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays($dueDays ?? 30)->toDateString(),
            ]);

            foreach ($fees as $fee) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'student_fee_id' => $fee->id,
                    'description' => $fee->feeStructure?->feeType?->name ?? 'Student fee',
                    'original_amount' => $fee->original_amount,
                    'discount_amount' => $fee->discount_amount,
                    'net_amount' => $fee->net_amount,
                ]);
            }

            return $invoice->fresh([
                'student',
                'academicYear',
                'issuedBy',
                'items.studentFee.feeStructure.feeType',
            ]);
        });
    }

    public function send(int $id): Invoice
    {
        $invoice = $this->invoiceRepo->findOrFail($id);

        if ($invoice->status === 'cancelled') {
            throw new \DomainException('Cancelled invoices cannot be sent.');
        }

        if ($invoice->status === 'sent') {
            return $invoice;
        }

        if ($invoice->status !== 'draft') {
            throw new \DomainException('Only draft invoices can be sent.');
        }

        $this->invoiceRepo->update($id, ['status' => 'sent']);

        return $this->invoiceRepo->findOrFail($id);
    }

    public function cancel(int $id): Invoice
    {
        $invoice = $this->invoiceRepo->findOrFail($id);

        if ($invoice->status === 'cancelled') {
            return $invoice;
        }

        if ((float) $invoice->paid_amount > 0) {
            throw new \DomainException('An invoice with payments cannot be cancelled. Refund the payments first.');
        }

        $this->invoiceRepo->update($id, ['status' => 'cancelled']);

        return $this->invoiceRepo->findOrFail($id);
    }

    public function getOverdue(): Collection
    {
        return $this->invoiceRepo->getModel()
            ->with(['student', 'academicYear', 'issuedBy', 'items.studentFee.feeStructure.feeType'])
            ->overdue()
            ->orderBy('due_date')
            ->get();
    }

    public function getByStudent(int $studentId): Collection
    {
        return $this->invoiceRepo->getModel()
            ->where('student_id', $studentId)
            ->with(['academicYear', 'issuedBy', 'items.studentFee.feeStructure.feeType'])
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->get();
    }
}
