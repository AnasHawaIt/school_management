<?php

namespace Modules\Library\Services;

use Modules\Library\Entities\Member;
use Modules\Library\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\Events\BorrowingEvents\BorrowingUpdateed;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;
use Modules\Library\Entities\Reservation;
use Illuminate\Validation\ValidationException;
use Modules\Library\Events\BookEvents\BookAvailable;

class TransactionService
{
    protected $repo;

    public function __construct(TransactionRepositoryInterface $repo)
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

        return $transaction;
    }

    public function forceDelete($id)
    {
        $Transaction= $this->repo->forceDelete($id);

        event(new BorrowingRejected($Transaction));

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

        $limit = $member->max_active_loans ?? config('library.max_active_loans_per_member');
        if ($member->transactions()->whereIn('status', ['borrowed', 'late'])->count() >= $limit) {
            throw ValidationException::withMessages([
                'member_id' => 'The member has reached the maximum number of active loans.',
            ]);
        }

        $data['borrow_date'] ??= now()->toDateString();
        $data['due_date'] ??= now()->addDays(config('library.loan_days'))->toDateString();
        $transaction = $this->repo->create($data);

        event(new BorrowingCreated($transaction, auth()->id()));

        return $transaction;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $before = $this->repo->findById($id);
        $Transaction= $this->repo->update($id, $data);

        event(new BorrowingUpdateed($Transaction, auth()->id()));
        if (
            in_array($before->status, ['borrowed', 'late'], true)
            && $Transaction->status === 'returned'
        ) {
            event(new BookAvailable($Transaction->book));
        }

        return $Transaction;
    }

    public function renew($id)
    {
        $transaction = $this->repo->findById($id);
        if (!in_array($transaction->status, ['borrowed', 'late'], true)) {
            throw ValidationException::withMessages(['transaction' => 'Only active borrowings can be renewed.']);
        }
        if ($transaction->renewal_count >= $transaction->max_renewals) {
            throw ValidationException::withMessages(['transaction' => 'Renewal limit has been reached.']);
        }
        if (Reservation::where('book_id', $transaction->book_id)
            ->where('status', 'pending')
            ->where('member_id', '!=', $transaction->member_id)
            ->exists()) {
            throw ValidationException::withMessages(['transaction' => 'This book has a pending reservation.']);
        }

        $transaction->update([
            'due_date' => $transaction->due_date->addDays(config('library.renewal_days')),
            'renewal_count' => $transaction->renewal_count + 1,
            'status' => 'borrowed',
        ]);

        return $transaction->refresh();
    }

    public function delete($id)
    {
        $Transaction = $this->repo->findById($id);

        if (!$Transaction) {
            throw new \Exception('Borrowing not found');
        }

        $this->repo->delete($id);

        event(new BorrowingRejected($Transaction));
        if (in_array($Transaction->status, ['borrowed', 'late'], true)) {
            event(new BookAvailable($Transaction->book));
        }

        return true;
    }

}
