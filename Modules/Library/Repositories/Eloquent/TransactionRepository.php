<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Filters\TransactionFilter;
use Modules\Library\app\Enums\BorrowingStatus;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionOnlyTrashed()
    {
        return Borrowing::onlyTrashed()
            ->with(['member.user', 'book', 'copy', 'fine'])
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    public function restore($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = Borrowing::withTrashed()
                ->lockForUpdate()
                ->findOrFail($id);

            if (!$transaction->trashed()) {
                return $transaction;
            }

            /*
             * If the restored borrowing is active,
             * its physical copy must become borrowed again.
             */
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

                if ($copy->status !== 'available') {
                    throw new \RuntimeException(
                        'The physical copy is not available for restoration.'
                    );
                }

                $copy->update([
                    'status' => 'borrowed',
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

    public function isAvailable(int $bookId): bool
    {
        return $this->availableCopiesCount($bookId) > 0;
    }

    /**
     * Physical Copies are the source of truth.
     */
    public function availableCopiesCount(int $bookId): int
    {
        Book::query()
            ->findOrFail($bookId);

        return BookCopy::query()
            ->where('book_id', $bookId)
            ->where('status', 'available')
            ->count();
    }

    public function returnBook(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {
            $transaction = Borrowing::query()
                ->with(['book', 'copy'])
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

            /*
             * Release the physical copy.
             */
            if ($transaction->copy_id) {
                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {
                    $copy->update([
                        'status' => 'available',
                    ]);
                }
            }

            $transaction->update([
                'status' => BorrowingStatus::RETURNED->value,
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

    public function forceDelete($id)
    {
        $transaction = Borrowing::withTrashed()
            ->findOrFail($id);

        $transaction->forceDelete();

        return $transaction;
    }

    public function getAll($request)
    {
        $query = Borrowing::query();

        $query = (new TransactionFilter($request))
            ->apply($query);

        return $query
            ->with(['member.user', 'book', 'copy', 'fine'])
            ->latest()
            ->paginate($request->get('per_page', 10));
    }

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
     * Create a borrowing using a physical book copy.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $book = Book::query()
                ->findOrFail($data['book_id']);

            /*
             * If a specific physical copy was requested,
             * validate and lock it.
             */
            if (!empty($data['copy_id'])) {
                $copy = BookCopy::query()
                    ->where('id', $data['copy_id'])
                    ->where('book_id', $book->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($copy->status !== 'available') {
                    throw new \RuntimeException(
                        'The selected book copy is not available.'
                    );
                }
            } else {
                /*
                 * Otherwise automatically assign the first
                 * available physical copy.
                 */
                $copy = BookCopy::query()
                    ->where('book_id', $book->id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->first();

                if (!$copy) {
                    throw new \RuntimeException(
                        'No available physical copy.'
                    );
                }
            }

            /*
             * Mark the physical copy as borrowed.
             */
            $copy->update([
                'status' => 'borrowed',
            ]);

            $data['copy_id'] = $copy->id;

            $transaction = Borrowing::create($data);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }

    public function update($id, array $data): Borrowing
    {
        return DB::transaction(function () use ($id, $data) {
            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $oldBookId = $transaction->book_id;
            $oldCopyId = $transaction->copy_id;

            /*
             * Because Borrowing casts status to BorrowingStatus,
             * convert it to its scalar value for internal comparisons.
             */
            $oldStatus = $transaction->status instanceof BorrowingStatus
                ? $transaction->status->value
                : $transaction->status;

            $newBookId = $data['book_id'] ?? $oldBookId;

            $newCopyId = array_key_exists('copy_id', $data)
                ? $data['copy_id']
                : $oldCopyId;

            $newStatus = $data['status'] ?? $oldStatus;

            if ($newStatus instanceof BorrowingStatus) {
                $newStatus = $newStatus->value;
            }

            $wasActive = in_array($oldStatus, [
                BorrowingStatus::BORROWED->value,
                BorrowingStatus::LATE->value,
            ], true);

            $willBeActive = in_array($newStatus, [
                BorrowingStatus::BORROWED->value,
                BorrowingStatus::LATE->value,
            ], true);

            $bookChanged = $oldBookId != $newBookId;
            $copyChanged = $oldCopyId != $newCopyId;

            /*
             * ACTIVE -> ACTIVE
             *
             * Example:
             * Borrowed Book A / Copy 1
             *        ->
             * Borrowed Book B / Copy 5
             */
            if (
                $wasActive &&
                $willBeActive &&
                ($bookChanged || $copyChanged)
            ) {
                /*
                 * Release old physical copy.
                 */
                if ($oldCopyId) {
                    $oldCopy = BookCopy::query()
                        ->lockForUpdate()
                        ->find($oldCopyId);

                    if ($oldCopy) {
                        $oldCopy->update([
                            'status' => 'available',
                        ]);
                    }
                }

                /*
                 * Get new physical copy.
                 */
                if ($newCopyId) {
                    $newCopy = BookCopy::query()
                        ->where('id', $newCopyId)
                        ->where('book_id', $newBookId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($newCopy->status !== 'available') {
                        throw new \RuntimeException(
                            'The selected book copy is not available.'
                        );
                    }
                } else {
                    $newCopy = BookCopy::query()
                        ->where('book_id', $newBookId)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if (!$newCopy) {
                        throw new \RuntimeException(
                            'No available physical copy for the selected book.'
                        );
                    }
                }

                $newCopy->update([
                    'status' => 'borrowed',
                ]);

                $data['copy_id'] = $newCopy->id;
            }

            /*
             * ACTIVE -> INACTIVE
             *
             * Release the physical copy.
             */
            if ($wasActive && !$willBeActive) {
                if ($oldCopyId) {
                    $copy = BookCopy::query()
                        ->lockForUpdate()
                        ->find($oldCopyId);

                    if ($copy) {
                        $copy->update([
                            'status' => 'available',
                        ]);
                    }
                }

                if (!isset($data['returned_at'])) {
                    $data['returned_at'] = now();
                }

                if (!isset($data['return_date'])) {
                    $data['return_date'] = now()->toDateString();
                }
            }

            /*
             * INACTIVE -> ACTIVE
             *
             * Acquire a physical copy.
             */
            if (!$wasActive && $willBeActive) {
                if ($newCopyId) {
                    $newCopy = BookCopy::query()
                        ->where('id', $newCopyId)
                        ->where('book_id', $newBookId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($newCopy->status !== 'available') {
                        throw new \RuntimeException(
                            'The selected book copy is not available.'
                        );
                    }
                } else {
                    $newCopy = BookCopy::query()
                        ->where('book_id', $newBookId)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    if (!$newCopy) {
                        throw new \RuntimeException(
                            'No available physical copy.'
                        );
                    }
                }

                $newCopy->update([
                    'status' => 'borrowed',
                ]);

                $data['copy_id'] = $newCopy->id;
            }

            /*
             * Final safety check:
             * the physical copy must belong to the selected book.
             */
            if (
                $willBeActive &&
                !empty($data['copy_id'])
            ) {
                $copy = BookCopy::query()
                    ->find($data['copy_id']);

                if (!$copy) {
                    throw new \RuntimeException(
                        'The selected physical copy was not found.'
                    );
                }

                if ($copy->book_id != $newBookId) {
                    throw new \RuntimeException(
                        'The selected copy does not belong to the selected book.'
                    );
                }
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

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $isActive = in_array($transaction->status, [
                BorrowingStatus::BORROWED,
                BorrowingStatus::LATE,
            ], true);

            /*
             * Release the physical copy.
             */
            if ($isActive && $transaction->copy_id) {
                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {
                    $copy->update([
                        'status' => 'available',
                    ]);
                }
            }

            /*
             * Soft delete the borrowing.
             */
            $transaction->delete();

            return $transaction;
        });
    }

    public function markLost(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {
            $transaction = Borrowing::query()
                ->with(['book', 'copy'])
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

            /*
             * Mark the physical copy as lost.
             */
            if ($transaction->copy_id) {
                $copy = BookCopy::query()
                    ->lockForUpdate()
                    ->find($transaction->copy_id);

                if ($copy) {
                    $copy->update([
                        'status' => 'lost',
                    ]);
                }
            }

            /*
             * Mark the borrowing as lost.
             */
            $transaction->update([
                'status' => BorrowingStatus::LOST->value,
            ]);

            return $transaction->fresh([
                'member.user',
                'book',
                'copy',
                'fine',
            ]);
        });
    }
}
