<?php

namespace Modules\Library\Repositories\Eloquent;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Repositories\Interfaces\BookCopyRepositoryInterface;

class BookCopyRepository implements BookCopyRepositoryInterface
{
    public function paginateByBook(
        Book $book,
        Request $request
    ): LengthAwarePaginator {
        return $book->copies()
            ->with('book')
            ->latest('id')
            ->paginate(
                min(
                    max(
                        (int) $request->get('per_page', 20),
                        1
                    ),
                    100
                )
            );
    }

    public function findById(
        int $id
    ): BookCopy {
        return BookCopy::query()
            ->with('book')
            ->findOrFail($id);
    }

    public function findByIdForUpdate(
        int $id
    ): BookCopy {
        return BookCopy::query()
            ->lockForUpdate()
            ->findOrFail($id);
    }

    public function createForBook(
        Book $book,
        array $data
    ): BookCopy {
        return $book->copies()->create($data);
    }

    public function update(
        BookCopy $copy,
        array $data
    ): BookCopy {
        $copy->update($data);

        return $copy->fresh([
            'book',
        ]);
    }

    public function delete(
        BookCopy $copy
    ): bool {
        return (bool) $copy->delete();
    }

    public function findActiveTransaction(
        BookCopy $copy
    ): ?object {
        return $copy->transactions()
            ->whereIn('status', [
                'borrowed',
                'late',
            ])
            ->latest('id')
            ->first();
    }
}
