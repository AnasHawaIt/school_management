<?php

namespace Modules\Finance\Repositories;

use App\Repositories\BaseRepository;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Entities\StudentFee;
use Illuminate\Database\Eloquent\Collection;

class StudentFeeRepository extends BaseRepository implements StudentFeeRepositoryInterface
{
    public function __construct(StudentFee $model)
    {
        parent::__construct($model);
    }

    public function getByStudent(int $studentId, ?int $yearId = null): Collection
    {
        $q = $this->model->with(['feeStructure.feeType', 'discount', 'payments'])
            ->where('student_id', $studentId);
        if ($yearId) $q->where('academic_year_id', $yearId);
        return $q->get();
    }

    public function getOverdue(): Collection
    {
        return $this->model->with(['student', 'feeStructure.feeType'])
            ->overdue()->get();
    }

    public function getUnpaidByYear(int $yearId): Collection
    {
        return $this->model->with(['student', 'feeStructure.feeType'])
            ->where('academic_year_id', $yearId)
            ->unpaid()->get();
    }

    public function getTotalByYear(int $yearId): array
    {
        $rows = $this->model->where('academic_year_id', $yearId)->get();
        return [
            'total_expected' => $rows->sum('net_amount'),
            'total_paid'     => $rows->sum('paid_amount'),
            'total_remaining'=> $rows->sum('remaining_amount'),
        ];
    }
}
