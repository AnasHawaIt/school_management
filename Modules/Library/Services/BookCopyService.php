<?php

namespace Modules\Library\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\Repositories\Interfaces\BookCopyRepositoryInterface;

class BookCopyService
{
    public function __construct(
        protected BookCopyRepositoryInterface $repository,
        protected FineService $fineService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    public function paginate(
        Book $book,
             $request
    ) {
        return $this->repository->paginateByBook(
            $book,
            $request
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(
        Book $book,
        array $data
    ): BookCopy {
        return DB::transaction(function () use (
            $book,
            $data
        ) {
            /*
             * A newly created physical copy
             * is always AVAILABLE.
             */
            $data['status'] =
                BookCopiesStatus::AVAILABLE;

            $copy = $this->repository->createForBook(
                $book,
                $data
            );

            return $copy->fresh([
                'book',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        BookCopy $copy,
        array $data
    ): BookCopy {
        return DB::transaction(function () use (
            $copy,
            $data
        ) {
            $copy = $this->repository
                ->findByIdForUpdate($copy->id);

            /*
             * Status is not part of the normal
             * update operation.
             */
            unset($data['status']);

            $copy = $this->repository->update(
                $copy,
                $data
            );

            return $copy->fresh([
                'book',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(
        BookCopy $copy
    ): bool {
        return DB::transaction(function () use (
            $copy
        ) {
            $copy = $this->repository
                ->findByIdForUpdate($copy->id);

            /*
             * Borrowed or reserved copies cannot
             * be deleted.
             */
            if (
                in_array(
                    $copy->status,
                    [
                        BookCopiesStatus::BORROWED,
                        BookCopiesStatus::RESERVED,
                    ],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Borrowed or reserved copies cannot be deleted.'
                );
            }

            /*
             * Do not delete a copy that has
             * an active borrowing.
             */
            $transaction = $this->repository
                ->findActiveTransaction($copy);

            if ($transaction) {
                throw new RuntimeException(
                    'This copy has an active borrowing and cannot be deleted.'
                );
            }

            return $this->repository->delete($copy);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Status Change
    |--------------------------------------------------------------------------
    |
    | This method is intentionally separate from
    | normal update().
    |
    */

    public function changeStatus(
        BookCopy $copy,
        BookCopiesStatus $newStatus
    ): BookCopy {
        return DB::transaction(function () use (
            $copy,
            $newStatus
        ) {
            $copy = $this->repository
                ->findByIdForUpdate($copy->id);

            $oldStatus = $copy->status;

            if ($oldStatus === $newStatus) {
                return $copy->fresh([
                    'book',
                ]);
            }

            $this->validateStatusChange(
                $copy,
                $newStatus
            );

            $copy = $this->repository->update(
                $copy,
                [
                    'status' => $newStatus,
                ]
            );

            /*
             * Lost / damaged copy may require
             * compensation.
             */
            if (
                in_array(
                    $newStatus,
                    [
                        BookCopiesStatus::LOST,
                        BookCopiesStatus::DAMAGED,
                    ],
                    true
                )
            ) {
                $this->handleCompensation(
                    $copy,
                    $newStatus
                );
            }

            return $copy->fresh([
                'book',
            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Status Validation
    |--------------------------------------------------------------------------
    */

    protected function validateStatusChange(
        BookCopy $copy,
        BookCopiesStatus $newStatus
    ): void {
        /*
         * RESERVED is controlled by reservation
         * workflow.
         */
        if (
            $copy->status ===
            BookCopiesStatus::RESERVED
        ) {
            throw new RuntimeException(
                'Reserved copies can only be released through the reservation or borrowing workflow.'
            );
        }

        /*
         * BORROWED is controlled by borrowing
         * workflow.
         */
        if (
            $copy->status ===
            BookCopiesStatus::BORROWED
        ) {
            if (
                in_array(
                    $newStatus,
                    [
                        BookCopiesStatus::AVAILABLE,
                        BookCopiesStatus::RESERVED,
                    ],
                    true
                )
            ) {
                throw new RuntimeException(
                    'Borrowed copies can only be released by the borrowing return workflow.'
                );
            }
        }

        /*
         * A lost copy cannot magically become
         * available without being recovered.
         *
         * We allow maintenance/damaged only if
         * your business flow needs it.
         */
        if (
            $copy->status ===
            BookCopiesStatus::LOST
            &&
            $newStatus !==
            BookCopiesStatus::LOST
        ) {
            throw new RuntimeException(
                'Lost copies must be processed through the recovery workflow.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Compensation
    |--------------------------------------------------------------------------
    */

    protected function handleCompensation(
        BookCopy $copy,
        BookCopiesStatus $status
    ): void {
        $transaction = $this->repository
            ->findActiveTransaction($copy);

        /*
         * No active borrowing means there is
         * nobody to charge.
         */
        if (!$transaction) {
            return;
        }

        /*
         * Use manually configured replacement cost
         * first.
         */
        $amount = $copy->replacement_cost;

        /*
         * Otherwise use Library configuration:
         *
         * library.lost
         * library.damaged
         */
        if ($amount === null) {
            $amount = config(
                "library.{$status->value}"
            );
        }

        if ($amount === null) {
            throw new RuntimeException(
                "No compensation amount configured for {$status->value} copy."
            );
        }

        $this->fineService->create([
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'status' => 'unpaid',
            'notes' =>
                "Copy marked {$status->value}.",
        ]);
    }
}
