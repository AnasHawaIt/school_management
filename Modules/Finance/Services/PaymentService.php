<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\PaymentRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\PaymentServiceInterface;
use Modules\Finance\Entities\Payment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentService implements PaymentServiceInterface
{
    public function __construct(
        protected PaymentRepositoryInterface     $paymentRepo,
        protected StudentFeeRepositoryInterface  $studentFeeRepo,
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
        DB::beginTransaction();
        try {
            $studentFee = $this->studentFeeRepo->findOrFail($data['student_fee_id']);

            // Validate amount doesn't exceed remaining
            if ($data['amount'] > $studentFee->remaining_amount) {
                throw new \Exception('Payment amount exceeds remaining balance of ' . $studentFee->remaining_amount);
            }

            $data['student_id']  = $studentFee->student_id;
            $data['received_by'] = $data['received_by'] ?? Auth::id();
            $data['paid_at']     = $data['paid_at'] ?? now();
            $data['status']      = 'completed';

            $payment = $this->paymentRepo->create($data);

            // Update student fee
            $studentFee->paid_amount      += $data['amount'];
            $studentFee->remaining_amount -= $data['amount'];
            $studentFee->save();
            $studentFee->updatePaymentStatus();

            DB::commit();
            return $payment->load(['studentFee', 'receivedBy']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function refundPayment(int $id, string $reason = ''): Payment
    {
        DB::beginTransaction();
        try {
            $payment = $this->paymentRepo->findOrFail($id);

            if ($payment->status !== 'completed') {
                throw new \Exception('Only completed payments can be refunded');
            }

            $payment->update(['status' => 'refunded', 'notes' => $reason]);

            // Reverse student fee
            $studentFee = $payment->studentFee;
            $studentFee->paid_amount      -= $payment->amount;
            $studentFee->remaining_amount += $payment->amount;
            $studentFee->save();
            $studentFee->updatePaymentStatus();

            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getByStudent(int $studentId): Collection
    {
        return $this->paymentRepo->getByStudent($studentId);
    }

    public function getReport(?string $from, ?string $to): array
    {
        return [
            'total_collected' => $this->paymentRepo->getTotalCollected($from, $to),
            'by_method'       => [
                'cash'          => $this->paymentRepo->getTotalCollected($from, $to),
                'bank_transfer' => 0,
                'stripe'        => 0,
                'paypal'        => 0,
            ],
            'payments' => $from && $to
                ? $this->paymentRepo->getByDateRange($from, $to)
                : $this->paymentRepo->all(),
        ];
    }
}
