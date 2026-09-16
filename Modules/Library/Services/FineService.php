<?php

namespace Modules\Library\Services;

use Illuminate\Support\Facades\DB;
use Modules\Library\Events\FinesEvents\FineCreated;
use Modules\Library\Events\FinesEvents\FinePaid;
use Modules\Library\Events\FinesEvents\FineWaived;
use RuntimeException;
use Modules\Library\Entities\Fine;
use Modules\Library\Repositories\Interfaces\FineRepositoryInterface;
use Modules\Library\app\Enums\FineStatus;

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

            if ($fine->status !== FineStatus::UNPAID) {
                throw new RuntimeException(
                    'Only unpaid fines can be paid.'
                );
            }

            $fine = $this->repository->update($fine, [
                'status' => FineStatus::PAID,
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

            if ($fine->status !== FineStatus::UNPAID) {
                throw new RuntimeException(
                    'Only unpaid fines can be waived.'
                );
            }

            $fine = $this->repository->update($fine, [
                'status' => FineStatus::WAIVED,
                'waived_at' => now(),
                'waived_by' => auth()->id(),
            ]);

            event(new FineWaived($fine));

            return $fine;
        });
    }
}
