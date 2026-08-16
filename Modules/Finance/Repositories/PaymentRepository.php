<?php

namespace Modules\Finance\Repositories;

use App\Repositories\BaseRepository;
use Modules\Finance\Contracts\Repositories\PaymentRepositoryInterface;
use Modules\Finance\Entities\Payment;
use Illuminate\Database\Eloquent\Collection;

class PaymentRepository extends BaseRepository implements PaymentRepositoryInterface
{
    public function __construct(Payment $model)
    {
        parent::__construct($model);
    }

    public function getByStudent(int $studentId): Collection
    {
        return $this->model->with(['studentFee.feeStructure.feeType', 'receivedBy'])
            ->where('student_id', $studentId)
            ->orderByDesc('paid_at')
            ->get();
    }

    public function getByMethod(string $method): Collection
    {
        return $this->model->where('method', $method)->get();
    }

    public function getTotalCollected(?string $from = null, ?string $to = null): float
    {
        $q = $this->model->where('status', 'completed');
        if ($from) $q->where('paid_at', '>=', $from);
        if ($to)   $q->where('paid_at', '<=', $to);
        return (float) $q->sum('amount');
    }

    public function getByDateRange(string $from, string $to): Collection
    {
        return $this->model->with(['student', 'receivedBy'])
            ->where('status', 'completed')
            ->whereBetween('paid_at', [$from, $to])
            ->orderByDesc('paid_at')
            ->get();
    }
}
