<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Filters\TransactionFilter;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{
    /**
     * Get only soft deleted transactions.
     */
    public function getTransactionOnlyTrashed()
    {
        return Borrowing::onlyTrashed()
            ->with(['member.user', 'book', 'copy', 'fine'])
            ->latest()
            ->paginate(request()->get('per_page', 10));
    }

    /**
     * Restore a soft deleted transaction.
     *
     * If the transaction was active before deletion,
     * the related book copy must become borrowed again.
     */
    public function restore($id)
    {
        return DB::transaction(function () use ($id) {

            /** @var Borrowing $transaction */
            $transaction = Borrowing::withTrashed()
                ->lockForUpdate()
                ->findOrFail($id);

            if (!$transaction->trashed()) {
                return $transaction;
            }

            /*
            |--------------------------------------------------------------------------
            | Restore physical copy
            |--------------------------------------------------------------------------
            */
            if (
                in_array($transaction->status, ['borrowed', 'late'], true)
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

            /*
            |--------------------------------------------------------------------------
            | Restore transaction
            |--------------------------------------------------------------------------
            */
            $transaction->restore();

            /*
            |--------------------------------------------------------------------------
            | Sync inventory
            |--------------------------------------------------------------------------
            */
            $book = Book::query()
                ->lockForUpdate()
                ->find($transaction->book_id);

            if ($book) {
                $this->syncPhysicalInventory($book);
            }

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

    public function availableCopiesCount(int $bookId): int
    {
        $book = Book::query()
            ->findOrFail($bookId);

        if ($book->copies()->exists()) {
            return $book->copies()
                ->where('status', 'available')
                ->count();
        }

        return (int) $book->copies;
    }


    public function returnBook(int $id): Borrowing
    {
        return DB::transaction(function () use ($id) {

            // Lock borrowing row
            $transaction = Borrowing::query()
                ->with(['book', 'copy'])
                ->lockForUpdate()
                ->find($id);

            if (!$transaction) {
                throw new \Exception('Borrowing not found');
            }

            // Prevent returning an already returned transaction
            if (!in_array($transaction->status, ['borrowed', 'late'], true)) {
                throw new \RuntimeException(
                    'This borrowing is already returned.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | 1. Return physical copy
            |--------------------------------------------------------------------------
            */
            if ($transaction->copy_id) {

                $copy = BookCopy::query()
                    ->where('id', $transaction->copy_id)
                    ->lockForUpdate()
                    ->first();

                if ($copy) {
                    $copy->update([
                        'status' => 'available',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2. Update borrowing
            |--------------------------------------------------------------------------
            */
            $transaction->update([
                'status'      => 'returned',
                'return_date' => now()->toDateString(),
                'returned_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | 3. Sync book available copies
            |--------------------------------------------------------------------------
            */
            $book = $transaction->book;

            if ($book) {

                // If this book uses physical copies,
                // calculate available copies from BookCopy.
                if ($book->copies()->exists()) {

                    $availableCopies = $book->copies()
                        ->where('status', 'available')
                        ->count();

                    $book->update([
                        'copies' => $availableCopies,
                    ]);

                } else {

                    // Fallback for books without physical copies
                    $book->increment('copies');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 4. Refresh relations
            |--------------------------------------------------------------------------
            */
            return $transaction->fresh([
                'book',
                'member',
                'copy',
            ]);
        });
    }

    /**
     * Permanently delete a transaction.
     *
     * Force deleting a transaction must NOT change inventory because
     * inventory was already released when the transaction was soft deleted.
     */
    public function forceDelete($id)
    {
        $transaction = Borrowing::withTrashed()->findOrFail($id);

        $transaction->forceDelete();

        return $transaction;
    }

    /**
     * Get all transactions.
     */
    public function getAll($request)
    {
        $query = Borrowing::query();

        $query = (new TransactionFilter($request))->apply($query);

        return $query
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->latest()
            ->paginate($request->get('per_page', 10));
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
     * Create a borrowing transaction.
     *
     * Physical copies are the source of truth when they exist.
     */
    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            /** @var Book $book */
            $book = Book::query()
                ->lockForUpdate()
                ->findOrFail($data['book_id']);

            $copy = null;

            /*
            |--------------------------------------------------------------------------
            | Physical copies exist
            |--------------------------------------------------------------------------
            */
            if ($book->copies()->exists()) {

                /*
                |--------------------------------------------------------------------------
                | Specific copy requested
                |--------------------------------------------------------------------------
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
                    |--------------------------------------------------------------------------
                    | No copy specified -> automatically select an available copy
                    |--------------------------------------------------------------------------
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
                |--------------------------------------------------------------------------
                | Borrow the physical copy
                |--------------------------------------------------------------------------
                */
                $copy->update([
                    'status' => 'borrowed',
                ]);

                $data['copy_id'] = $copy->id;

                /*
                |--------------------------------------------------------------------------
                | Sync book inventory
                |--------------------------------------------------------------------------
                */
                $this->syncPhysicalInventory($book);

            } else {

                /*
                |--------------------------------------------------------------------------
                | No physical copies table records
                |--------------------------------------------------------------------------
                | Use books.copies as inventory.
                |--------------------------------------------------------------------------
                */
                if ($book->copies <= 0) {
                    throw new \RuntimeException(
                        'Book not available.'
                    );
                }

                $book->decrement('copies');
            }

            /*
            |--------------------------------------------------------------------------
            | Create transaction
            |--------------------------------------------------------------------------
            */
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
     * Update a borrowing transaction.
     *
     * Handles:
     * - changing book
     * - changing physical copy
     * - changing status to returned
     * - changing status from returned to active
     */
    public function update($id, array $data): Borrowing
    {
        return DB::transaction(function () use ($id, $data) {

            /** @var Borrowing $transaction */
            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $oldBookId = $transaction->book_id;
            $oldCopyId = $transaction->copy_id;
            $oldStatus = $transaction->status;

            $newBookId = $data['book_id'] ?? $oldBookId;
            $newCopyId = array_key_exists('copy_id', $data)
                ? $data['copy_id']
                : $oldCopyId;

            $newStatus = $data['status'] ?? $oldStatus;

            $wasActive = in_array(
                $oldStatus,
                ['borrowed', 'late'],
                true
            );

            $willBeActive = in_array(
                $newStatus,
                ['borrowed', 'late'],
                true
            );

            $bookChanged = $oldBookId != $newBookId;
            $copyChanged = $oldCopyId != $newCopyId;

            /*
            |--------------------------------------------------------------------------
            | Case 1: Active transaction changes book/copy
            |--------------------------------------------------------------------------
            */
            if (
                $wasActive &&
                $willBeActive &&
                ($bookChanged || $copyChanged)
            ) {

                /*
                |--------------------------------------------------------------------------
                | Release old physical copy
                |--------------------------------------------------------------------------
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
                |--------------------------------------------------------------------------
                | Sync old book
                |--------------------------------------------------------------------------
                */
                $oldBook = Book::query()
                    ->lockForUpdate()
                    ->find($oldBookId);

                if ($oldBook) {
                    $this->syncPhysicalInventory($oldBook);
                }

                /*
                |--------------------------------------------------------------------------
                | Lock new book
                |--------------------------------------------------------------------------
                */
                $newBook = Book::query()
                    ->lockForUpdate()
                    ->findOrFail($newBookId);

                /*
                |--------------------------------------------------------------------------
                | If physical copies exist
                |--------------------------------------------------------------------------
                */
                if ($newBook->copies()->exists()) {

                    /*
                    | If no copy supplied, automatically select one.
                    */
                    if (!$newCopyId) {

                        $newCopy = BookCopy::query()
                            ->where('book_id', $newBook->id)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();

                        if (!$newCopy) {
                            throw new \RuntimeException(
                                'No available physical copy for the selected book.'
                            );
                        }

                    } else {

                        /*
                        | Specific copy supplied.
                        */
                        $newCopy = BookCopy::query()
                            ->where('id', $newCopyId)
                            ->where('book_id', $newBook->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                        if ($newCopy->status !== 'available') {
                            throw new \RuntimeException(
                                'The selected book copy is not available.'
                            );
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Borrow new physical copy
                    |--------------------------------------------------------------------------
                    */
                    $newCopy->update([
                        'status' => 'borrowed',
                    ]);

                    $data['copy_id'] = $newCopy->id;

                    /*
                    |--------------------------------------------------------------------------
                    | Sync new book
                    |--------------------------------------------------------------------------
                    */
                    $this->syncPhysicalInventory($newBook);

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | No physical copies
                    |--------------------------------------------------------------------------
                    */
                    if ($newBook->copies <= 0) {
                        throw new \RuntimeException(
                            'The selected book is not available.'
                        );
                    }

                    $newBook->decrement('copies');

                    /*
                    | Transaction has no physical copy.
                    */
                    $data['copy_id'] = null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Case 2: Active -> Returned
            |--------------------------------------------------------------------------
            */
            if ($wasActive && !$willBeActive) {

                /*
                |--------------------------------------------------------------------------
                | Release physical copy
                |--------------------------------------------------------------------------
                */
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

                /*
                |--------------------------------------------------------------------------
                | Sync book inventory
                |--------------------------------------------------------------------------
                */
                $book = Book::query()
                    ->lockForUpdate()
                    ->find($oldBookId);

                if ($book) {
                    $this->syncPhysicalInventory($book);
                }

                /*
                |--------------------------------------------------------------------------
                | Set returned_at automatically if not provided
                |--------------------------------------------------------------------------
                */
                if (!isset($data['returned_at'])) {
                    $data['returned_at'] = now();
                }

                /*
                |--------------------------------------------------------------------------
                | Set return_date automatically if not provided
                |--------------------------------------------------------------------------
                */
                if (!isset($data['return_date'])) {
                    $data['return_date'] = now()->toDateString();
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Case 3: Returned -> Active
            |--------------------------------------------------------------------------
            */
            if (!$wasActive && $willBeActive) {

                $book = Book::query()
                    ->lockForUpdate()
                    ->findOrFail($newBookId);

                /*
                |--------------------------------------------------------------------------
                | Physical copies
                |--------------------------------------------------------------------------
                */
                if ($book->copies()->exists()) {

                    if (!$newCopyId) {

                        $newCopy = BookCopy::query()
                            ->where('book_id', $book->id)
                            ->where('status', 'available')
                            ->lockForUpdate()
                            ->first();

                        if (!$newCopy) {
                            throw new \RuntimeException(
                                'No available physical copy.'
                            );
                        }

                    } else {

                        $newCopy = BookCopy::query()
                            ->where('id', $newCopyId)
                            ->where('book_id', $book->id)
                            ->lockForUpdate()
                            ->firstOrFail();

                        if ($newCopy->status !== 'available') {
                            throw new \RuntimeException(
                                'The selected book copy is not available.'
                            );
                        }
                    }

                    $newCopy->update([
                        'status' => 'borrowed',
                    ]);

                    $data['copy_id'] = $newCopy->id;

                    $this->syncPhysicalInventory($book);

                } else {

                    if ($book->copies <= 0) {
                        throw new \RuntimeException(
                            'Book not available.'
                        );
                    }

                    $book->decrement('copies');

                    $data['copy_id'] = null;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Prevent changing copy_id without changing book incorrectly
            |--------------------------------------------------------------------------
            */
            if (
                $willBeActive &&
                isset($data['copy_id']) &&
                $data['copy_id'] &&
                $newBookId
            ) {

                $copy = BookCopy::query()
                    ->find($data['copy_id']);

                if ($copy && $copy->book_id != $newBookId) {
                    throw new \RuntimeException(
                        'The selected copy does not belong to the selected book.'
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update transaction
            |--------------------------------------------------------------------------
            */
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
     *
     * Active borrowing releases the physical copy/inventory.
     */
    public function delete($id)
    {
        return DB::transaction(function () use ($id) {

            /** @var Borrowing $transaction */
            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            $isActive = in_array(
                $transaction->status,
                ['borrowed', 'late'],
                true
            );

            if ($isActive) {

                /*
                |--------------------------------------------------------------------------
                | Release physical copy
                |--------------------------------------------------------------------------
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

                /*
                |--------------------------------------------------------------------------
                | Sync book inventory
                |--------------------------------------------------------------------------
                */
                $book = Book::query()
                    ->lockForUpdate()
                    ->find($transaction->book_id);

                if ($book) {

                    /*
                    | If physical copies exist, calculate from them.
                    */
                    if ($book->copies()->exists()) {

                        $this->syncPhysicalInventory($book);

                    } else {

                        /*
                        | Otherwise increase simple inventory.
                        */
                        $book->increment('copies');
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Soft delete transaction
            |--------------------------------------------------------------------------
            */
            $transaction->delete();

            return $transaction;
        });
    }

    public function markLost(int $id): Borrowing
{
    return DB::transaction(function () use ($id) {

        /** @var Borrowing $transaction */
        $transaction = Borrowing::query()
            ->with(['book', 'copy'])
            ->lockForUpdate()
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Validate current status
        |--------------------------------------------------------------------------
        */

        if (!in_array($transaction->status, ['borrowed', 'late'], true)) {
            throw new \RuntimeException(
                'Only active borrowings can be marked as lost.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | 1. Mark physical copy as lost
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | 2. Update borrowing
        |--------------------------------------------------------------------------
        */

        $transaction->update([
            'status' => 'lost',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 3. Sync book inventory
        |--------------------------------------------------------------------------
        |
        | Physical copies are the source of truth.
        | A lost copy must NOT count as available.
        |--------------------------------------------------------------------------
        */

        $book = Book::query()
            ->lockForUpdate()
            ->find($transaction->book_id);

        if ($book) {
            $this->syncPhysicalInventory($book);
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Refresh relations
        |--------------------------------------------------------------------------
        */

        return $transaction->fresh([
            'member.user',
            'book',
            'copy',
            'fine',
        ]);
    });
}


    /**
     * Synchronize books.copies with physical available copies.
     *
     * Physical copies are treated as the source of truth.
     */
    private function syncPhysicalInventory(Book $book): void
    {
        if ($book->copies()->exists()) {

            $availableCopies = $book->copies()
                ->where('status', 'available')
                ->count();

            $book->update([
                'copies' => $availableCopies,
            ]);
        }
    }

}
