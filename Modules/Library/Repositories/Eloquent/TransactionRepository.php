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

    public function getTransactionOnlyTrashed()
    {
        return Borrowing::onlyTrashed()
        ->paginate(request()->get('per_page', 10));
    }

    public function restore($id)
    {
        $transaction = Borrowing::withTrashed()->findOrFail($id);

        $transaction->restore();

        return $transaction;
    }

    public function forceDelete($id)
    {
        $transaction = Borrowing::withTrashed()->findOrFail($id);

        $transaction->forceDelete();

        return $transaction;
    }

    public function getAll($request)
    {
        $query = Borrowing::query();

        $query = (new TransactionFilter($request))->apply($query);

        return $query
            ->with(['member.user', 'book', 'copy', 'fine'])
            ->latest()
            ->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Borrowing::with(['book', 'member.user', 'copy', 'fine'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $book = Book::query()
                ->lockForUpdate()
                ->findOrFail($data['book_id']);

            $copy = null;
            if (!empty($data['copy_id'])) {
                $copy = BookCopy::query()
                    ->where('book_id', $book->id)
                    ->lockForUpdate()
                    ->findOrFail($data['copy_id']);

                if ($copy->status !== 'available') {
                    throw new \RuntimeException('Book copy not available');
                }
            }

            if ($book->copies <= 0) {
                throw new \RuntimeException('Book not available');
            }

            $book->decrement('copies');
            $copy?->update(['status' => 'borrowed']);
            $transaction = Borrowing::create($data);
            $this->syncPhysicalInventory($book);

            return $transaction;
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);
            $oldStatus = $transaction->status;
            $oldBookId = $transaction->book_id;
            $oldCopyId = $transaction->copy_id;
            $newStatus = $data['status'] ?? $oldStatus;
            $newBookId = $data['book_id'] ?? $oldBookId;
            $newCopyId = $data['copy_id'] ?? $oldCopyId;
            $wasActive = in_array($oldStatus, ['borrowed', 'late'], true);
            $isActive = in_array($newStatus, ['borrowed', 'late'], true);

            if ($oldBookId !== $newBookId && $wasActive) {
                Book::query()->lockForUpdate()->findOrFail($oldBookId)->increment('copies');
            }

            if ($wasActive && $oldCopyId !== $newCopyId && $oldCopyId) {
                BookCopy::query()->lockForUpdate()->findOrFail($oldCopyId)->update(['status' => 'available']);
            }

            if ($oldBookId !== $newBookId && $isActive) {
                $newBook = Book::query()->lockForUpdate()->findOrFail($newBookId);
                if ($newBook->copies <= 0) {
                    throw new \RuntimeException('Book not available');
                }
                $newBook->decrement('copies');
            } elseif ($newCopyId && $isActive && $oldCopyId !== $newCopyId) {
                $newCopy = BookCopy::query()
                    ->where('book_id', $newBookId)
                    ->lockForUpdate()
                    ->findOrFail($newCopyId);
                if ($newCopy->status !== 'available') {
                    throw new \RuntimeException('Book copy not available');
                }
                $newCopy->update(['status' => 'borrowed']);
            } elseif (!$wasActive && $isActive) {
                $book = Book::query()->lockForUpdate()->findOrFail($newBookId);
                if ($book->copies <= 0) {
                    throw new \RuntimeException('Book not available');
                }
                $book->decrement('copies');
                if ($newCopyId) {
                    $copy = BookCopy::query()
                        ->where('book_id', $newBookId)
                        ->lockForUpdate()
                        ->findOrFail($newCopyId);
                    if ($copy->status !== 'available') {
                        throw new \RuntimeException('Book copy not available');
                    }
                    $copy->update(['status' => 'borrowed']);
                }
            } elseif ($wasActive && !$isActive) {
                Book::query()->lockForUpdate()->findOrFail($oldBookId)->increment('copies');
                if ($oldCopyId) {
                    BookCopy::query()->lockForUpdate()->findOrFail($oldCopyId)->update(['status' => 'available']);
                }
            }

            if ($newStatus === 'returned') {
                $data['return_date'] ??= today()->toDateString();
                $data['returned_at'] ??= now();
            } elseif ($isActive) {
                $data['return_date'] = null;
                $data['returned_at'] = null;
            }

            $transaction->update($data);
            $this->syncPhysicalInventory(Book::findOrFail($newBookId));

            return $transaction;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $transaction = Borrowing::query()
                ->lockForUpdate()
                ->findOrFail($id);

            if (in_array($transaction->status, ['borrowed', 'late'], true)) {
                Book::query()
                    ->lockForUpdate()
                    ->findOrFail($transaction->book_id)
                    ->increment('copies');
                if ($transaction->copy_id) {
                    BookCopy::query()
                        ->lockForUpdate()
                        ->findOrFail($transaction->copy_id)
                        ->update(['status' => 'available']);
                }

                $this->syncPhysicalInventory(Book::findOrFail($transaction->book_id));
            }

            return $transaction->delete();
        });
    }

    private function syncPhysicalInventory(Book $book): void
    {
        if ($book->copies()->exists()) {
            $book->update([
                'copies' => $book->copies()->where('status', 'available')->count(),
            ]);
        }
    }
}
