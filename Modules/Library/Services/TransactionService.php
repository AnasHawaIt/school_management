<?php

namespace Modules\Library\Services;

use Illuminate\Validation\ValidationException;
use Modules\Library\Entities\Borrowing;
use Modules\Library\Entities\Member;
use Modules\Library\Entities\Reservation;
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
    protected $repo;

    public function __construct(TransactionRepositoryInterface $repo,)
    {
        $this->repo = $repo;
    }

    public function getTransactionOnlyTrashed()
    {
        return $this->repo->getTransactionOnlyTrashed();
    }

    public function restore($id)
    {
        $transaction= $this->repo->restore($id);

        event(new BorrowingRestored($transaction));

        return $transaction;
    }

    public function forceDelete($id)
    {
        $Transaction= $this->repo->forceDelete($id);

        event(new BorrowingForceDeleted($Transaction));

        return true;

    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

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
                ->whereIn('status', ['borrowed', 'late'])
                ->count() >= $limit
        ) {
            throw ValidationException::withMessages([
                'member_id' => 'The member has reached the maximum number of active loans.',
            ]);
        }

        // فحص توفر الكتاب
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

        event(new BorrowingCreated($transaction, auth()->id()));

        return $transaction;
    }

    public function approve(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== 'pending') {
            throw ValidationException::withMessages([
                'transaction' => 'Only pending borrowings can be approved.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        event(new BorrowingApproved(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    public function reject(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== 'pending') {
            throw ValidationException::withMessages([
                'transaction' => 'Only pending borrowings can be rejected.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => 'rejected',
        ]);

        event(new BorrowingRejected(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    public function pickup(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== 'approved') {
            throw ValidationException::withMessages([
                'transaction' => 'Only approved borrowings can be picked up.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => 'borrowed',
            'picked_up_at' => now(),
        ]);

        event(new BorrowingPickedUp(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    public function cancel(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, [
            'pending',
            'approved',
        ], true)) {
            throw ValidationException::withMessages([
                'transaction' => 'This borrowing cannot be cancelled.',
            ]);
        }

        $transaction = $this->repo->update($id, [
            'status' => 'cancelled',
        ]);

        event(new BorrowingCancelled(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    public function returnBook(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if (!$transaction) {
            throw new \Exception('Borrowing not found');
        }

        if (!in_array($transaction->status, ['borrowed', 'late'], true)) {
            throw ValidationException::withMessages([
                'transaction' => 'This borrowing is already returned.',
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

    public function markLost(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, [
            'borrowed',
            'late',
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

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $before = $this->repo->findById($id);

        $transaction = $this->repo->update($id, $data);

        event(new BorrowingUpdated(
            $transaction,
            auth()->id()
        ));

        if (
            in_array($before->status, ['borrowed', 'late'], true)
            && $transaction->status === 'returned'
        ) {
            event(new BorrowingReturned(
                $transaction,
                auth()->id()
            ));

            event(new BookAvailable(
                $transaction->book
            ));
        }

        return $transaction;
    }

    public function renew($id)
    {
        $transaction = $this->repo->findById($id);

        if (!in_array($transaction->status, ['borrowed', 'late'], true)) {
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
                ->where('status', 'pending')
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

            'status' => 'borrowed',
        ]);

        $transaction = $transaction->refresh();

        event(new BorrowingRenewed(
            $transaction,
            auth()->id()
        ));

        return $transaction;
    }

    public function markOverdue(int $id): Borrowing
    {
        $transaction = $this->repo->findById($id);

        if ($transaction->status !== 'borrowed') {
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
            'status' => 'late',
        ]);

        event(new BorrowingOverdue(
            $transaction
        ));

        return $transaction;
    }

    public function delete($id)
    {
        $Transaction = $this->repo->findById($id);

        if (!$Transaction) {
            throw new \Exception('Borrowing not found');
        }

        $this->repo->delete($id);

        event(new BorrowingDeleted($Transaction));

        if (in_array($Transaction->status, ['borrowed', 'late'], true)) {
            event(new BookAvailable($Transaction->book));
        }

        return true;
    }

}
