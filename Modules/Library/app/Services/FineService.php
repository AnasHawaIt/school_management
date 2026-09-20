<?php

namespace Modules\Library\app\Services;

use Illuminate\Support\Facades\DB;
use Modules\Library\app\Events\FinesEvents\FineCreated;
use Modules\Library\app\Events\FinesEvents\FinePaid;
use Modules\Library\app\Events\FinesEvents\FineWaived;
use Modules\Library\app\Entities\Fine;
use Modules\Library\app\Repositories\Interfaces\FineRepositoryInterface;
use RuntimeException;

class FineService
{
    public function __construct(
        protected FineRepositoryInterface $repository
    ) {}

    public function create(array $data): Fine
    {
        return DB::transaction(function () use ($data) {

            $fine = $this->repository->create($data);

            event(new FineCreated($fine));

            return $fine->fresh();
        });
    }

    public function pay(Fine $fine): Fine
    {
        return DB::transaction(function () use ($fine) {

            $fine = $this->repository->findForUpdate($fine->id);

            if ($fine->status !== 'unpaid') {
                throw new RuntimeException(
                    'Only unpaid fines can be paid.'
                );
            }

            $fine = $this->repository->update($fine, [
                'status' => 'paid',
                'paid_at' => now(),
                'paid_by' => auth()->id(),
            ]);

            event(new FinePaid($fine));

            return $fine;
        });
    }

    public function waive(Fine $fine): Fine
    {
        return DB::transaction(function () use ($fine) {

            $fine = $this->repository->findForUpdate($fine->id);

            if ($fine->status !== 'unpaid') {
                throw new RuntimeException(
                    'Only unpaid fines can be waived.'
                );
            }

            $fine = $this->repository->update($fine, [
                'status' => 'waived',
                'waived_at' => now(),
                'waived_by' => auth()->id(),
            ]);

            event(new FineWaived($fine));

            return $fine;
        });
    }
}
