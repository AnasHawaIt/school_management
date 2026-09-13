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

    public function isAvailable(int $bookId): bool
    {
        return $this->availableCopiesCount($bookId) > 0;
    }

    /**
     * Physical copies are the source of truth.
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
     * At this stage:
     *
     * Borrowing: pending -> approved
     * Copy:      available -> reserved
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
     * Borrowing: approved -> borrowed
     * Copy:      reserved -> borrowed
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
     *
     * Pending:
     *      no physical copy
     *
     * Approved:
     *      reserved -> available
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
     * Create borrowing request.
     *
     * IMPORTANT:
     * No physical copy is reserved here.
     *
     * Borrowing:
     * pending
     *
     * Copy:
     * remains available
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

    public function update($id, array $data): Borrowing
    {
        return DB::transaction(function () use ($id, $data) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $oldBookId = $transaction->book_id;
            $oldCopyId = $transaction->copy_id;

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

            /*
             * Lifecycle statuses should be handled
             * through dedicated methods.
             */
            if (
                $newStatus !== $oldStatus
                && in_array($newStatus, [
                    BorrowingStatus::APPROVED->value,
                    BorrowingStatus::BORROWED->value,
                    BorrowingStatus::CANCELLED->value,
                ], true)
            ) {
                throw new \RuntimeException(
                    'Use the dedicated borrowing lifecycle operation for this status change.'
                );
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
             */
            if (
                $wasActive &&
                $willBeActive &&
                ($bookChanged || $copyChanged)
            ) {

                if ($oldCopyId) {

                    $oldCopy = BookCopy::query()
                        ->lockForUpdate()
                        ->find($oldCopyId);

                    if ($oldCopy) {
                        $oldCopy->update([
                            'status' => BookCopiesStatus::AVAILABLE,
                        ]);
                    }
                }

                if ($newCopyId) {

                    $newCopy = BookCopy::query()
                        ->where('id', $newCopyId)
                        ->where('book_id', $newBookId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($newCopy->status !== BookCopiesStatus::AVAILABLE) {
                        throw new \RuntimeException(
                            'The selected book copy is not available.'
                        );
                    }

                } else {

                    $newCopy = BookCopy::query()
                        ->where('book_id', $newBookId)
                        ->where('status', BookCopiesStatus::AVAILABLE)
                        ->lockForUpdate()
                        ->first();

                    if (!$newCopy) {
                        throw new \RuntimeException(
                            'No available physical copy for the selected book.'
                        );
                    }
                }

                $newCopy->update([
                    'status' => BookCopiesStatus::BORROWED,
                ]);

                $data['copy_id'] = $newCopy->id;
            }

            /*
             * ACTIVE -> INACTIVE
             */
            if ($wasActive && !$willBeActive) {

                if ($oldCopyId) {

                    $copy = BookCopy::query()
                        ->lockForUpdate()
                        ->find($oldCopyId);

                    if ($copy) {
                        $copy->update([
                            'status' => BookCopiesStatus::AVAILABLE,
                        ]);
                    }
                }

                $data['returned_at'] ??= now();
                $data['return_date'] ??= now()->toDateString();
            }

            /*
             * INACTIVE -> ACTIVE
             */
            if (!$wasActive && $willBeActive) {

                if ($newCopyId) {

                    $newCopy = BookCopy::query()
                        ->where('id', $newCopyId)
                        ->where('book_id', $newBookId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($newCopy->status !== BookCopiesStatus::AVAILABLE) {
                        throw new \RuntimeException(
                            'The selected book copy is not available.'
                        );
                    }

                } else {

                    $newCopy = BookCopy::query()
                        ->where('book_id', $newBookId)
                        ->where('status', BookCopiesStatus::AVAILABLE)
                        ->lockForUpdate()
                        ->first();

                    if (!$newCopy) {
                        throw new \RuntimeException(
                            'No available physical copy.'
                        );
                    }
                }

                $newCopy->update([
                    'status' => BookCopiesStatus::BORROWED,
                ]);

                $data['copy_id'] = $newCopy->id;
            }

            /*
             * Final safety check.
             */
            if (
                $willBeActive
                && !empty($data['copy_id'])
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
}
