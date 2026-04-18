<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Support\Facades\DB;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Transaction;
use Modules\Library\Filters\TransactionFilter;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionRepository implements TransactionRepositoryInterface
{

    public function getTransactionOnlyTrashed()
    {
        return Transaction::onlyTrashed()
        ->paginate(request()->get('per_page', 10));
    }

    public function restore($id)
    {
        $transaction = Transaction::withTrashed()->findOrFail($id);

        $transaction->restore();

        return $transaction;
    }

    public function forceDelete($id)
    {
        $transaction = Transaction::withTrashed()->findOrFail($id);

        $transaction->forceDelete();

        return $transaction;
    }

    public function getAll($request)
    {
        $query = Transaction::query();

        $query = (new TransactionFilter($request))->apply($query);

        return $query
            ->with(['member', 'book'])
            ->latest()
            ->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Transaction::with(['book', 'member'])->findOrFail($id);
    }

    public function create(array $data)
    {

        $book = Book::findOrFail($data['book_id']);

        if ($book->copies <= 0) {
            throw new \Exception('Book not available');
        }

        $book->decrement('copies');

        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $transaction = $this->findById($id);

            if (
                isset($data['status']) &&
                $transaction->status !== 'returned' &&
                $data['status'] === 'returned'
            ) {
                $transaction->book->increment('copies');
            }

            $transaction->update($data);

            return $transaction;
        });
    }

    public function delete($id)
    {
        $transaction = $this->findById($id);
        return $transaction->delete();
    }
}
