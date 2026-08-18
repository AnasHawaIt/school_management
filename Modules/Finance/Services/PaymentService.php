<?php

namespace Modules\Finance\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Finance\Contracts\Repositories\PaymentRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\PaymentServiceInterface;
use Modules\Finance\Entities\InvoiceItem;
use Modules\Finance\Entities\Payment;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        protected PaymentRepositoryInterface $paymentRepo,
        protected StudentFeeRepositoryInterface $studentFeeRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->paymentRepo->all();
    }

    public function getById(int $id): ?Payment
    {
        return $this->paymentRepo->find($id);
    }

    public function recordPayment(array $data): Payment
    {
        return DB::transaction(function () use ($data) {
            $studentFee = $this->studentFeeRepo->findOrFail($data['student_fee_id']);
            $amount = round((float) $data['amount'], 2);

            if ($amount <= 0) {
                throw new \DomainException('Payment amount must be greater than zero.');
            }

            if ($amount > (float) $studentFee->remaining_amount) {
                throw new \DomainException(
                    'Payment amount exceeds remaining balance of ' . $studentFee->remaining_amount
                );
            }

            $data['student_id'] = $studentFee->student_id;
            $data['received_by'] = $data['received_by'] ?? Auth::id();
            $data['paid_at'] = $data['paid_at'] ?? now();
            $data['status'] = 'completed';

            $payment = $this->paymentRepo->create($data);

            $studentFee->paid_amount = round((float) $studentFee->paid_amount + $amount, 2);
            $studentFee->remaining_amount = max(0, round((float) $studentFee->remaining_amount - $amount, 2));
            $studentFee->updatePaymentStatus();

            $this->syncInvoicesForStudentFee($studentFee->id);

            return $payment->load(['studentFee', 'receivedBy']);
        });
    }

    public function refundPayment(int $id, string $reason = ''): Payment
    {
        return DB::transaction(function () use ($id, $reason) {
            $payment = $this->paymentRepo->findOrFail($id);

            if ($payment->status !== 'completed') {
                throw new \DomainException('Only completed payments can be refunded.');
            }

            $studentFee = $payment->studentFee;
            $newPaid = round((float) $studentFee->paid_amount - (float) $payment->amount, 2);

            if ($newPaid < 0) {
                throw new \DomainException('Refund would make the student fee balance invalid.');
            }

            $payment->update([
                'status' => 'refunded',
                'notes' => $reason ?: $payment->notes,
            ]);

            $studentFee->paid_amount = $newPaid;
            $studentFee->remaining_amount = round(
                max(0, (float) $studentFee->net_amount - $newPaid),
                2
            );
            $studentFee->updatePaymentStatus();

            $this->syncInvoicesForStudentFee($studentFee->id);

            return $payment->fresh(['studentFee', 'receivedBy']);
        });
    }

    public function getByStudent(int $studentId): Collection
    {
        return $this->paymentRepo->getByStudent($studentId);
    }

    public function getReport(?string $from, ?string $to): array
    {
        $query = $this->paymentRepo->getModel()->where('status', 'completed');

        if ($from) {
            $query->where('paid_at', '>=', $from);
        }
        if ($to) {
            $query->where('paid_at', '<=', $to);
        }

        $payments = $query->orderByDesc('paid_at')->get();

        return [
            'total_collected' => (float) $payments->sum('amount'),
            'by_method' => $payments->groupBy('method')->map(fn ($rows) => (float) $rows->sum('amount'))->all(),
            'payments' => $payments,
        ];
    }

    protected function syncInvoicesForStudentFee(int $studentFeeId): void
    {
        $invoiceIds = InvoiceItem::query()
            ->where('student_fee_id', $studentFeeId)
            ->pluck('invoice_id')
            ->unique();

        foreach ($invoiceIds as $invoiceId) {
            $invoice = \Modules\Finance\Entities\Invoice::query()
                ->with('items.studentFee')
                ->find($invoiceId);

            if (!$invoice || $invoice->status === 'cancelled') {
                continue;
            }

            $paid = (float) $invoice->items->sum(fn ($item) => (float) $item->studentFee->paid_amount);
            $net = (float) $invoice->net_amount;

            $invoice->paid_amount = min($net, $paid);
            $invoice->remaining_amount = max(0, $net - $invoice->paid_amount);

            if ($invoice->remaining_amount <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->paid_amount > 0) {
                $invoice->status = 'partial';
            } elseif ($invoice->due_date && $invoice->due_date->isPast() && $invoice->status === 'sent') {
                $invoice->status = 'overdue';
            }

            $invoice->save();
        }
    }
}
