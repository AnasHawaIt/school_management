<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Filters\TransactionFilter;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\app\Enums\BorrowingStatus;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Get only trashed transactions.
     */
    public function getTransactionOnlyTrashed()
    {
        return Borrowing::onlyTrashed()
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    /**
     * Restore soft deleted transaction.
     */
    public function restore($id)
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::withTrashed()
                ->lockForUpdate()
                ->findOrFail($id);

            if (!$transaction->trashed()) {
                return $transaction;
            }

            if (
                in_array($transaction->status, [
                    BorrowingStatus::BORROWED,
                    BorrowingStatus::LATE,
                ], true)
                && $transaction->copy_id
            ) {
                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->findOrFail($transaction->copy_id);

                if ($copy->status !== BookCopiesStatus::AVAILABLE) {
                    throw new \RuntimeException(
                        'The physical copy is not available for restoration.'
                    );
                }

                $copy->update([
                    'status' => BookCopiesStatus::BORROWED,
                ]);
            }

            $transaction->restore();

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Check book availability.
     *
     * Physical copies are the source of truth.
     */
    public function isAvailable(int $bookId): bool
    {
        return $this->availableCopiesCount($bookId) > 0;
    }

    /**
     * Count available physical copies.
     */
    public function availableCopiesCount(int $bookId): int
    {
        Book::query()->findOrFail($bookId);

        return BookCopy::query()
            ->where('book_id', $bookId)
            ->where('status', BookCopiesStatus::AVAILABLE)
            ->count();
    }

    /**
     * Approve a pending borrowing.
     *
     * Borrowing:
     * pending -> approved
     *
     * Copy:
     * available -> reserved
     */
    public function approve(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($transaction->status !== BorrowingStatus::PENDING) {
                throw new \RuntimeException(
                    'Only pending borrowings can be approved.'
                );
            }

            /*
             * Serialize approvals for the same book. The copy query below
             * also locks the selected row, while the book lock prevents two
             * concurrent approvals from selecting the same inventory state.
             */
            Book::query()
                ->lockForUpdate()
                ->findOrFail($transaction->book_id);

            /*
             * If a specific copy was requested,
             * validate it again under lock.
             */
            if ($transaction->copy_id) {

                $copy = BookCopy::query()
                    ->where('id', $transaction->copy_id)
                    ->where('book_id', $transaction->book_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($copy->status !== BookCopiesStatus::AVAILABLE) {
                    throw new \RuntimeException(
                        'The requested physical copy is no longer available.'
                    );
                }

            } else {

                /*
                 * Otherwise select the first available physical copy.
                 */
                $copy = BookCopy::query()
                    ->where('book_id', $transaction->book_id)
                    ->where('status', BookCopiesStatus::AVAILABLE)
                    ->orderBy('id')
                    ->lockForUpdate()
                    ->first();

                if (!$copy) {
                    throw new \RuntimeException(
                        'No available physical copy for this borrowing.'
                    );
                }
            }

            /*
             * Reserve the physical copy.
             */
            $copy->update([
                'status' => BookCopiesStatus::RESERVED,
            ]);

            /*
             * Approve the borrowing.
             */
            $transaction->update([
                'copy_id' => $copy->id,
                'status' => BorrowingStatus::APPROVED,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Pickup an approved borrowing.
     *
     * Borrowing:
     * approved -> borrowed
     *
     * Copy:
     * reserved -> borrowed
     */
    public function pickup(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($transaction->status !== BorrowingStatus::APPROVED) {
                throw new \RuntimeException(
                    'Only approved borrowings can be picked up.'
                );
            }

            if (!$transaction->copy_id) {
                throw new \RuntimeException(
                    'No physical copy has been reserved for this borrowing.'
                );
            }

            $copy = BookCopy::query()
                ->where('id', $transaction->copy_id)
                ->where('book_id', $transaction->book_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($copy->status !== BookCopiesStatus::RESERVED) {
                throw new \RuntimeException(
                    'The reserved physical copy is not available for pickup.'
                );
            }

            /*
             * The member physically receives the copy.
             */
            $copy->update([
                'status' => BookCopiesStatus::BORROWED,
            ]);

            $transaction->update([
                'status' => BorrowingStatus::BORROWED,
                'picked_up_at' => now(),
            ]);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Cancel pending or approved borrowing.
     */
    public function cancel(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if (!in_array($transaction->status, [
                BorrowingStatus::PENDING,
                BorrowingStatus::APPROVED,
            ], true)) {
                throw new \RuntimeException(
                    'This borrowing cannot be cancelled.'
                );
            }

            /*
             * Approved borrowing has a reserved physical copy.
             */
            if (
                $transaction->status === BorrowingStatus::APPROVED
                && $transaction->copy_id
            ) {
                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if (
                    $copy
                    && $copy->status === BookCopiesStatus::RESERVED
                ) {
                    $copy->update([
                        'status' => BookCopiesStatus::AVAILABLE,
                    ]);
                }
            }

            $transaction->update([
                'status' => BorrowingStatus::CANCELLED,
            ]);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Return borrowed/late book.
     *
     * Borrowing:
     * borrowed/late -> returned
     *
     * Copy:
     * borrowed -> available
     */
    public function returnBook(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->with([
                    'book',
                    'copy',
                ])
                ->lockForUpdate()
                ->find($id);

            if (!$transaction) {
                throw new \Exception('Borrowing not found');
            }

            if (!in_array($transaction->status, [
                BorrowingStatus::BORROWED,
                BorrowingStatus::LATE,
            ], true)) {
                throw new \RuntimeException(
                    'This borrowing is already returned.'
                );
            }

            if ($transaction->copy_id) {

                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {
                    $copy->update([
                        'status' => BookCopiesStatus::AVAILABLE,
                    ]);
                }
            }

            $transaction->update([
                'status' => BorrowingStatus::RETURNED,
                'return_date' => now()->toDateString(),
                'returned_at' => now(),
            ]);

            return $transaction->fresh([
                'book',
                'member',
                'copy',
            ]);
        });
    }

    /**
     * Get all transactions.
     */
    public function getAll($request)
    {
        $query = Borrowing::query();

        $query = (new TransactionFilter($request))
            ->apply($query);

        return $query
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->latest()
            ->paginate(
                $request->get('per_page', 10)
            );
    }

    /**
     * Find transaction by ID.
     */
    public function findById($id)
    {
        return Borrowing::with([
            'book',
            'member.user',
            'copy',
            'fine',
        ])->findOrFail($id);
    }

    /**
     * Create borrowing request.
     *
     * No physical copy is reserved here.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            Book::query()
                ->findOrFail($data['book_id']);

            /*
             * copy_id represents the ACTUAL physical copy
             * assigned to the borrowing.
             *
             * Therefore it remains NULL until approval.
             */
            $data['copy_id'] = null;

            $data['status'] = $data['status']
                ?? BorrowingStatus::PENDING->value;

            $transaction = Borrowing::create($data);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Update normal transaction fields.
     *
     * Dedicated lifecycle operations must be used
     * for lifecycle status changes.
     */
    public function update($id, array $data): Borrowing
    {
        return DB::transaction(function () use ($id, $data) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if ($transaction->status !== BorrowingStatus::PENDING) {
                throw new \RuntimeException(
                    'Only pending borrowings can be updated.'
                );
            }

            $transaction->update($data);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Soft delete transaction.
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            /*
             * Active borrowing -> borrowed copy.
             * Approved borrowing -> reserved copy.
             */
            if ($transaction->copy_id) {

                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {

                    if (
                        $transaction->status === BorrowingStatus::BORROWED
                        || $transaction->status === BorrowingStatus::LATE
                    ) {
                        $copy->update([
                            'status' => BookCopiesStatus::AVAILABLE,
                        ]);
                    }

                    if (
                        $transaction->status === BorrowingStatus::APPROVED
                        && $copy->status === BookCopiesStatus::RESERVED
                    ) {
                        $copy->update([
                            'status' => BookCopiesStatus::AVAILABLE,
                        ]);
                    }
                }
            }

            $transaction->delete();

            return $transaction;
        });
    }

    /**
     * Mark borrowing as lost.
     *
     * Borrowing:
     * borrowed/late -> lost
     *
     * Copy:
     * borrowed -> lost
     */
    public function markLost(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            $transaction = Borrowing::query()
                ->with([
                    'book',
                    'copy',
                ])
                ->lockForUpdate()
                ->findOrFail($id);

            if (!in_array($transaction->status, [
                BorrowingStatus::BORROWED,
                BorrowingStatus::LATE,
            ], true)) {
                throw new \RuntimeException(
                    'Only active borrowings can be marked as lost.'
                );
            }

            if ($transaction->copy_id) {

                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {
                    $copy->update([
                        'status' => BookCopiesStatus::LOST,
                    ]);
                }
            }

            $transaction->update([
                'status' => BorrowingStatus::LOST,
            ]);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    /**
     * Permanently delete transaction.
     */
    public function forceDelete($id)
    {
        $transaction = Borrowing::withTrashed()
            ->findOrFail($id);

        $transaction->forceDelete();

        return $transaction;
    }
}
