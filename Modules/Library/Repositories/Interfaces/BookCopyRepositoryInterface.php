<?php

namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\BookCopy;

interface BookCopyRepositoryInterface
{
    public function paginateByBook(
        Book $book,
        Request $request
    ): LengthAwarePaginator;

    public function findById(int $id): BookCopy;

    public function findByIdForUpdate(int $id): BookCopy;

    public function createForBook(
        Book $book,
        array $data
    ): BookCopy;

    public function update(
        BookCopy $copy,
        array $data
    ): BookCopy;

    public function delete(BookCopy $copy): bool;

    public function findActiveTransaction(
        BookCopy $copy
    ): ?object;
}
