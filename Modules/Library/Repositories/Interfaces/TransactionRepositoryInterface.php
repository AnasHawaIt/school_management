<?php

namespace Modules\Library\Repositories\Interfaces;

use Illuminate\Http\Request;
use Modules\Library\Entities\Borrowing;

interface TransactionRepositoryInterface
{
    /**
     * Get only soft deleted transactions.
     */
    public function getTransactionOnlyTrashed();

    /**
     * Restore soft deleted transaction.
     */
    public function restore($id);

    /**
     * Permanently delete transaction.
     */
    public function forceDelete($id);

    /**
     * Get all transactions with filters and pagination.
     */
    public function getAll(Request $request);

    /**
     * Check if at least one physical copy is available.
     */
    public function isAvailable(int $bookId): bool;

    /**
     * Count available physical copies.
     */
    public function availableCopiesCount(int $bookId): int;

    /**
     * Find transaction by ID.
     */
    public function findById($id);

    /**
     * Create borrowing.
     */
    public function create(array $data);

    /**
     * Update normal transaction fields.
     */
    public function update($id, array $data);

    /**
     * Soft delete transaction.
     */
    public function delete($id);

    /*
     * ==========================================
     * BORROWING LIFECYCLE OPERATIONS
     * ==========================================
     */

    /**
     * PENDING -> APPROVED
     */
    public function approve(int $id): Borrowing;

    /**
     * APPROVED -> BORROWED
     */
    public function pickup(int $id): Borrowing;

    /**
     * PENDING/APPROVED -> CANCELLED
     */
    public function cancel(int $id): Borrowing;

    /**
     * BORROWED/LATE -> RETURNED
     */
    public function returnBook(int $id): Borrowing;

    /**
     * BORROWED/LATE -> LOST
     */
    public function markLost(int $id): Borrowing;
}
