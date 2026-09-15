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

    public function paginate(
        Book $book,
             $request
    ) {
        return $this->repository->paginateByBook(
            $book,
            $request
        );
    }

    public function create(
        Book $book,
        array $data
    ): BookCopy {
        return DB::transaction(function () use ($book, $data) {

            /*
             * Every newly created physical copy
             * must start as AVAILABLE.
             */
            $data['status'] = BookCopiesStatus::AVAILABLE;

            $copy = $this->repository->createForBook(
                $book,
                $data
            );

            return $copy->fresh();
        });
    }

    public function update(
        BookCopy $copy,
        array $data
    ): BookCopy {
        return DB::transaction(function () use ($copy, $data) {

            $copy = $this->repository->findByIdForUpdate(
                $copy->id
            );

            $this->validateStatusChange(
                $copy,
                $data
            );

            /*
             * Reserved copies cannot have their status
             * manually changed.
             */
            if (
                $copy->status === BookCopiesStatus::RESERVED
            ) {
                unset($data['status']);
            }

            $previousStatus = $copy->status;

            $copy = $this->repository->update(
                $copy,
                $data
            );

            /*
             * Lost / damaged copy may create a fine
             * for the active borrowing.
             */
            if (
                isset($data['status'])
                && in_array(
                    $data['status'],
                    [
                        BookCopiesStatus::LOST->value,
                        BookCopiesStatus::DAMAGED->value,
                    ],
                    true
                )
                && $previousStatus->value !== $data['status']
            ) {
                $this->handleCompensation(
                    $copy,
                    $data['status']
                );
            }

            return $copy->fresh();
        });
    }

    public function delete(BookCopy $copy): bool
    {
        return DB::transaction(function () use ($copy) {

            $copy = $this->repository->findByIdForUpdate(
                $copy->id
            );

            if (in_array(
                $copy->status,
                [
                    BookCopiesStatus::BORROWED,
                    BookCopiesStatus::RESERVED,
                ],
                true
            )) {
                throw new RuntimeException(
                    'Borrowed or reserved copies cannot be deleted.'
                );
            }

            return $this->repository->delete($copy);
        });
    }

    protected function validateStatusChange(
        BookCopy $copy,
        array $data
    ): void {
        if (!isset($data['status'])) {
            return;
        }

        $newStatus = $data['status'];

        /*
         * Normalize Enum → string.
         */
        if ($newStatus instanceof BookCopiesStatus) {
            $newStatus = $newStatus->value;
        }

        /*
         * Reserved copies are controlled by
         * reservation / borrowing workflow.
         */
        if (
            $copy->status === BookCopiesStatus::RESERVED
            && $newStatus !== BookCopiesStatus::RESERVED->value
        ) {
            throw new RuntimeException(
                'Reserved copies can only be released through the borrowing cancellation or pickup workflow.'
            );
        }

        /*
         * Borrowed copies cannot be manually returned
         * through BookCopy endpoint.
         */
        if (
            $copy->status === BookCopiesStatus::BORROWED
            && !in_array(
                $newStatus,
                [
                    BookCopiesStatus::BORROWED->value,
                    BookCopiesStatus::LOST->value,
                    BookCopiesStatus::DAMAGED->value,
                ],
                true
            )
        ) {
            throw new RuntimeException(
                'Borrowed copies can only be released by returning the active loan.'
            );
        }
    }

    protected function handleCompensation(
        BookCopy $copy,
        string $status
    ): void {
        $transaction = $this->repository
            ->findActiveTransaction($copy);

        if (!$transaction) {
            return;
        }

        $amount = $copy->replacement_cost
            ?? config(
                "library.{$status}_copy_compensation"
            );

        if ($amount === null) {
            throw new RuntimeException(
                "No compensation amount configured for {$status} copy."
            );
        }

        $this->fineService->create([
            'transaction_id' => $transaction->id,
            'amount' => $amount,
            'status' => 'unpaid',
            'notes' => "Copy marked {$status}.",
        ]);
    }
}
