<?php


namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Http\Request;
use Modules\Library\Entities\Borrowing;

interface TransactionRepositoryInterface
{
    public function getTransactionOnlyTrashed();
    public function restore($id);
    public function forceDelete($id);
    public function getAll(Request $request);
    public function isAvailable(int $bookId): bool;
    public function availableCopiesCount(int $bookId): int;
    public function returnBook(int $id): Borrowing;
    public function markLost(int $id): Borrowing;
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}
