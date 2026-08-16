<?php

namespace Modules\Finance\Services;

use Modules\Finance\Contracts\Repositories\FeeStructureRepositoryInterface;
use Modules\Finance\Contracts\Repositories\DiscountRepositoryInterface;
use Modules\Finance\Contracts\Repositories\StudentFeeRepositoryInterface;
use Modules\Finance\Contracts\Services\StudentFeeServiceInterface;
use Modules\Finance\Entities\StudentFee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class StudentFeeService implements StudentFeeServiceInterface
{
    public function __construct(
        protected StudentFeeRepositoryInterface  $studentFeeRepo,
        protected FeeStructureRepositoryInterface $feeStructureRepo,
        protected DiscountRepositoryInterface    $discountRepo,
    ) {}

    public function getAll(): Collection
    {
        return $this->studentFeeRepo->all();
    }

    public function getById(int $id): ?StudentFee
    {
        return $this->studentFeeRepo->find($id);
    }

    public function getByStudent(int $studentId, ?int $yearId = null): Collection
    {
        return $this->studentFeeRepo->getByStudent($studentId, $yearId);
    }

    public function assignFee(array $data): StudentFee
    {
        DB::beginTransaction();
        try {
            $structure = $this->feeStructureRepo->findOrFail($data['fee_structure_id']);
            $original  = (float) $structure->amount;
            $discount  = 0.0;

            if (!empty($data['discount_id'])) {
                $disc     = $this->discountRepo->findOrFail($data['discount_id']);
                $discount = $disc->calculateDiscount($original);
            }

            $net = $original - $discount;

            $fee = $this->studentFeeRepo->create([
                'student_id'       => $data['student_id'],
                'fee_structure_id' => $data['fee_structure_id'],
                'academic_year_id' => $structure->academic_year_id,
                'discount_id'      => $data['discount_id'] ?? null,
                'original_amount'  => $original,
                'discount_amount'  => $discount,
                'net_amount'       => $net,
                'paid_amount'      => 0,
                'remaining_amount' => $net,
                'status'           => 'unpaid',
                'due_date'         => $data['due_date'] ?? $structure->due_date,
                'notes'            => $data['notes'] ?? null,
            ]);

            DB::commit();
            return $fee->load(['feeStructure.feeType', 'discount']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Assign all active fee structures for a year to a student automatically
     */
    public function assignYearFees(int $studentId, int $yearId, int $gradeId): array
    {
        $structures = $this->feeStructureRepo->getModel()
            ->active()->forYear($yearId)->forGrade($gradeId)->get();

        $assigned = [];
        foreach ($structures as $structure) {
            $existing = $this->studentFeeRepo->getModel()
                ->where('student_id', $studentId)
                ->where('fee_structure_id', $structure->id)
                ->where('academic_year_id', $yearId)
                ->first();

            if (!$existing) {
                $assigned[] = $this->assignFee([
                    'student_id'       => $studentId,
                    'fee_structure_id' => $structure->id,
                ]);
            }
        }

        return $assigned;
    }

    public function getOverdue(): Collection
    {
        return $this->studentFeeRepo->getOverdue();
    }

    public function getSummaryByYear(int $yearId): array
    {
        return $this->studentFeeRepo->getTotalByYear($yearId);
    }

    public function waive(int $id, string $reason): bool
    {
        return $this->studentFeeRepo->update($id, [
            'status' => 'waived',
            'notes'  => $reason,
        ]);
    }
}
