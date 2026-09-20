<?php

namespace Modules\Library\app\Services;

use Illuminate\Validation\ValidationException;
use Modules\Library\app\Enums\BookCopiesStatus;
use Modules\Library\app\Enums\ReservationStatus;
use Modules\Library\app\Entities\BookCopy;
use Modules\Library\app\Entities\Borrowing;
use Modules\Library\app\Entities\Member;
use Modules\Library\app\Entities\Reservation;
use Modules\Library\app\Enums\BorrowingStatus;
use Modules\Library\app\Events\BorrowingEvents\BookAvailable;
use Modules\Library\app\Events\BorrowingEvents\BorrowingApproved;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCancelled;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\app\Events\BorrowingEvents\BorrowingDeleted;
use Modules\Library\app\Events\BorrowingEvents\BorrowingForceDeleted;
use Modules\Library\app\Events\BorrowingEvents\BorrowingLost;
use Modules\Library\app\Events\BorrowingEvents\BorrowingOverdue;
use Modules\Library\app\Events\BorrowingEvents\BorrowingPickedUp;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRenewed;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRestored;
use Modules\Library\app\Events\BorrowingEvents\BorrowingReturned;
use Modules\Library\app\Events\BorrowingEvents\BorrowingUpdated;
use Modules\Library\app\Repositories\Interfaces\TransactionRepositoryInterface;

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
        /*
         * ==========================================
         * PHYSICAL COPIES
         * ==========================================
         */

        $available = BookCopy::query()
            ->with(['book'])
            ->where('status', BookCopiesStatus::AVAILABLE)
            ->latest()
            ->get();

        $reserved = BookCopy::query()
            ->with([
                'book',
                'transactions' => function ($query) {
                    $query->where(
                        'status',
                        BorrowingStatus::APPROVED
                    )->latest();
                },
            ])
            ->where('status', BookCopiesStatus::RESERVED)
            ->latest()
            ->get();

        $borrowed = BookCopy::query()
            ->with([
                'book',
                'transactions' => function ($query) {
                    $query->whereIn('status', [
                        BorrowingStatus::BORROWED,
                        BorrowingStatus::LATE,
                    ])->latest();
                },
            ])
            ->where('status', BookCopiesStatus::BORROWED)
            ->latest()
            ->get();

        $lost = BookCopy::query()
            ->with(['book'])
            ->where('status', BookCopiesStatus::LOST)
            ->latest()
            ->get();

        /*
         * ==========================================
         * BORROWINGS
         * ==========================================
         */

        $late = Borrowing::query()
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->where('status', BorrowingStatus::LATE)
            ->latest()
            ->get();

        $approved = Borrowing::query()
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->where('status', BorrowingStatus::APPROVED)
            ->latest()
            ->get();

        $rejected = Borrowing::query()
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->where('status', BorrowingStatus::REJECTED)
            ->latest()
            ->get();

        $cancelled = Borrowing::query()
            ->with([
                'member.user',
                'book',
                'copy',
                'fine',
            ])
            ->where('status', BorrowingStatus::CANCELLED)
            ->latest()
            ->get();

        /*
         * ==========================================
         * COUNTS
         * ==========================================
         */

        return [
            'counts' => [
                'available' => $available->count(),
                'reserved' => $reserved->count(),
                'borrowed' => $borrowed->count(),
                'late' => $late->count(),
                'lost' => $lost->count(),

                'approved' => $approved->count(),
                'rejected' => $rejected->count(),
                'cancelled' => $cancelled->count(),
            ],

            'data' => [
                'available' => $available,
                'reserved' => $reserved,
                'borrowed' => $borrowed,
                'late' => $late,
                'lost' => $lost,

                'approved' => $approved,
                'rejected' => $rejected,
                'cancelled' => $cancelled,
            ],
        ];
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
