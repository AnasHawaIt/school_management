<?php

namespace Modules\Library\Services;

use Illuminate\Validation\ValidationException;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\app\Enums\ReservationStatus;
use Modules\Library\Entities\BookCopy;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;
use Modules\Library\app\Enums\BorrowingStatus;
use Modules\Library\Events\BorrowingEvents\BookAvailable;
use Modules\Library\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingDeleted;
use Modules\Library\Events\BorrowingEvents\BorrowingForceDeleted;
use Modules\Library\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\Events\BorrowingEvents\BorrowingRenewed;
use Modules\Library\Events\BorrowingEvents\BorrowingRestored;
use Modules\Library\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

class TransactionService
{
    protected TransactionRepositoryInterface $repo;

    public function __construct(TransactionRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Approve pending borrowing.
     */
    public function approve(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== BorrowingStatus::PENDING) {
            throw ValidationException::withMessages([
                'transaction' => 'Only pending borrowings can be approved.',
            ]);
        }

        $transaction = $this->repo->approve($id);

        event(new BorrowingApproved(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Reject pending borrowing.
     */
    public function reject(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== BorrowingStatus::PENDING) {
            throw ValidationException::withMessages([
                'transaction' => 'Only pending borrowings can be rejected.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => BorrowingStatus::REJECTED->value,
        ]);

        event(new BorrowingRejected(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Pickup approved borrowing.
     */
    public function pickup(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== BorrowingStatus::APPROVED) {
            throw ValidationException::withMessages([
                'transaction' => 'Only approved borrowings can be picked up.',
            ]);
        }

        $transaction = $this->repo->pickup($id);

        event(new BorrowingPickedUp(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Cancel pending/approved borrowing.
     */
    public function cancel(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        $transaction = $this->repo->cancel($id);

        event(new BorrowingCancelled(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Return borrowed/late book.
     */
    public function returnBook(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, [
            BorrowingStatus::BORROWED,
            BorrowingStatus::LATE,
        ], true)) {
            throw ValidationException::withMessages([
                'transaction' => 'Only borrowed or late borrowings can be returned.',
            ]);
        }

        $transaction = $this->repo->returnBook($id);

        event(new BorrowingReturned(
            $transaction,
            auth()->id()
        ));

        event(new BookAvailable(
            $transaction->book
        ));

        return $transaction;
    }

    /**
     * Mark borrowing as lost.
     */
    public function markLost(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, [
            BorrowingStatus::BORROWED,
            BorrowingStatus::LATE,
        ], true)) {
            throw ValidationException::withMessages([
                'transaction' => 'Only active borrowings can be marked as lost.',
            ]);
        }

        $transaction = $this->repo->markLost($id);

        event(new BorrowingLost(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Get borrowing and physical copies dashboard.
     */
    public function getStatusDashboard(): array
    {
        $copyCounts = BookCopy::query()
            ->select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $borrowingCounts = Borrowing::query()
            ->select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return [
            'counts' => [
                'available' => (int) ($copyCounts[BookCopiesStatus::AVAILABLE->value] ?? 0),
                'reserved' => (int) ($copyCounts[BookCopiesStatus::RESERVED->value] ?? 0),
                'borrowed' => (int) ($copyCounts[BookCopiesStatus::BORROWED->value] ?? 0),
                'late' => (int) ($borrowingCounts[BorrowingStatus::LATE->value] ?? 0),
                'lost' => (int) ($copyCounts[BookCopiesStatus::LOST->value] ?? 0),
                'approved' => (int) ($borrowingCounts[BorrowingStatus::APPROVED->value] ?? 0),
                'rejected' => (int) ($borrowingCounts[BorrowingStatus::REJECTED->value] ?? 0),
                'cancelled' => (int) ($borrowingCounts[BorrowingStatus::CANCELLED->value] ?? 0),
            ],
            'data' => [
                'available' => $this->paginateCopies(BookCopiesStatus::AVAILABLE),
                'reserved' => $this->paginateCopies(BookCopiesStatus::RESERVED, BorrowingStatus::APPROVED),
                'borrowed' => $this->paginateCopies(BookCopiesStatus::BORROWED, [BorrowingStatus::BORROWED, BorrowingStatus::LATE]),
                'lost' => $this->paginateCopies(BookCopiesStatus::LOST),
                'late' => $this->paginateBorrowings(BorrowingStatus::LATE),
                'approved' => $this->paginateBorrowings(BorrowingStatus::APPROVED),
                'rejected' => $this->paginateBorrowings(BorrowingStatus::REJECTED),
                'cancelled' => $this->paginateBorrowings(BorrowingStatus::CANCELLED),
            ],
        ];
    }

    private function paginateCopies(BookCopiesStatus $status, BorrowingStatus|array|null $transactionStatus = null)
    {
        $query = BookCopy::query()
            ->select(['id', 'book_id', 'barcode', 'status', 'location'])
            ->with(['book:id,title'])
            ->where('status', $status)
            ->latest();

        if ($transactionStatus !== null) {
            $statuses = is_array($transactionStatus) ? $transactionStatus : [$transactionStatus];
            $query->with(['transactions' => function ($query) use ($statuses) {
                $query->select(['id', 'book_id', 'copy_id', 'member_id', 'status', 'due_date'])
                    ->whereIn('status', $statuses)
                    ->latest();
            }]);
        }

        return $query->paginate((int) request()->integer('per_page', 10));
    }

    private function paginateBorrowings(BorrowingStatus $status)
    {
        return Borrowing::query()
            ->select(['id', 'member_id', 'book_id', 'copy_id', 'status', 'borrow_date', 'due_date'])
            ->with(['member:id,user_id', 'member.user:id,first_name,last_name', 'book:id,title', 'copy:id,barcode,status', 'fine'])
            ->where('status', $status)
            ->latest()
            ->paginate((int) request()->integer('per_page', 10));
    }

    /**
     * Get only trashed transactions.
     */
    public function getTransactionOnlyTrashed()
    {
        return $this->repo->getTransactionOnlyTrashed();
    }

    /**
     * Restore transaction.
     */
    public function restore($id)
    {
        $transaction = $this->repo->restore($id);

        event(new BorrowingRestored($transaction));

        return $transaction;
    }

    /**
     * Permanently delete transaction.
     */
    public function forceDelete($id)
    {
        $transaction = $this->repo->forceDelete($id);

        event(new BorrowingForceDeleted($transaction));

        return true;
    }

    /**
     * Get all transactions.
     */
    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    /**
     * Create borrowing request.
     */
    public function create(array $data)
    {
        $member = Member::findOrFail($data['member_id']);

        if ($member->membership_status !== 'active') {
            throw ValidationException::withMessages([
                'member_id' => 'Membership is not active.',
            ]);
        }

        $limit = $member->max_active_loans
            ?? config('library.max_active_loans_per_member');

        if (
            $member->transactions()
                ->whereIn('status', [
                    BorrowingStatus::BORROWED->value,
                    BorrowingStatus::LATE->value,
                ])
                ->count() >= $limit
        ) {
            throw ValidationException::withMessages([
                'member_id' => 'The member has reached the maximum number of active loans.',
            ]);
        }

        if (!$this->repo->isAvailable($data['book_id'])) {
            throw ValidationException::withMessages([
                'book_id' => 'This book is currently unavailable.',
            ]);
        }

        $data['borrow_date'] ??= now()->toDateString();

        $data['due_date'] ??= now()
            ->addDays(config('library.loan_days'))
            ->toDateString();

        $transaction = $this->repo->create($data);

        event(new BorrowingCreated(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Find transaction by ID.
     */
    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    /**
     * Update normal editable fields.
     *
     * Lifecycle operations should use their
     * dedicated methods.
     */
    public function update($id, array $data)
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== BorrowingStatus::PENDING) {
            throw ValidationException::withMessages([
                'transaction' => 'Only pending borrowings can be updated.',
            ]);
        }

        $transaction = $this->repo->update($id, $data);

        event(new BorrowingUpdated(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Renew active borrowing.
     */
    public function renew($id)
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, [
            BorrowingStatus::BORROWED,
            BorrowingStatus::LATE,
        ], true)) {
            throw ValidationException::withMessages([
                'transaction' => 'Only active borrowings can be renewed.',
            ]);
        }

        if ($transaction->renewal_count >= $transaction->max_renewals) {
            throw ValidationException::withMessages([
                'transaction' => 'Renewal limit has been reached.',
            ]);
        }

        if (
            Reservation::where('book_id', $transaction->book_id)
                ->where('status', ReservationStatus::PENDING)
                ->where('member_id', '!=', $transaction->member_id)
                ->exists()
        ) {
            throw ValidationException::withMessages([
                'transaction' => 'This book has a pending reservation.',
            ]);
        }

        $transaction->update([
            'due_date' => $transaction->due_date
                ->addDays(config('library.renewal_days')),

            'renewal_count' => $transaction->renewal_count + 1,

            'status' => BorrowingStatus::BORROWED->value,
        ]);

        $transaction = $transaction->refresh();

        event(new BorrowingRenewed(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    /**
     * Mark borrowing as overdue.
     */
    public function markOverdue(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== BorrowingStatus::BORROWED) {
            throw ValidationException::withMessages([
                'transaction' => 'Only borrowed transactions can become overdue.',
            ]);
        }

        if ($transaction->due_date->isFuture()) {
            throw ValidationException::withMessages([
                'transaction' => 'This borrowing is not overdue yet.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => BorrowingStatus::LATE->value,
        ]);

        event(new BorrowingOverdue(
            $transaction
        ));

        return $transaction;
    }

    /**
     * Soft delete transaction.
     */
    public function delete($id)
    {
        $transaction = $this->repo->findById($id);

        if (!$transaction) {
            throw new \Exception('Borrowing not found');
        }

        $this->repo->delete($id);

        event(new BorrowingDeleted($transaction));

        if (in_array($transaction->status, [
            BorrowingStatus::BORROWED,
            BorrowingStatus::LATE,
        ], true)) {
            event(new BookAvailable($transaction->book));
        }

        return true;
    }
}
