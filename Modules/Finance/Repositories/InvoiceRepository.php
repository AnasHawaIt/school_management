<?php

namespace Modules\Finance\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Finance\Contracts\Repositories\InvoiceRepositoryInterface;
use Modules\Finance\Entities\Invoice;

class InvoiceRepository extends BaseRepository implements InvoiceRepositoryInterface
{
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function all(array $columns = ['*']): Collection
    {
        return $this->model
            ->select($columns)
            ->with([
                'student',
                'academicYear',
                'issuedBy',
                'items.studentFee.feeStructure.feeType',
            ])
            ->orderByDesc('issue_date')
            ->orderByDesc('id')
            ->get();
    }

    public function find(int $id, array $columns = ['*']): ?Invoice
    {
        return $this->model
            ->select($columns)
            ->with([
                'student',
                'academicYear',
                'issuedBy',
                'items.studentFee.feeStructure.feeType',
            ])
            ->find($id);
    }
}
