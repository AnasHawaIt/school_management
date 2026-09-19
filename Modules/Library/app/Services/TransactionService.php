<?php

namespace Modules\Library\app\Services;

use Modules\Library\app\Entities\Member;
use Modules\Library\app\Events\BorrowingEvents\BorrowingCreated;
use Modules\Library\app\Events\BorrowingEvents\BorrowingRejected;
use Modules\Library\app\Events\BorrowingEvents\BorrowingUpdateed;
use Modules\Library\app\Repositories\Interfaces\TransactionRepositoryInterface;

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

        if ($member->status !== 'active') {
            throw new \Exception('Membership is not active');
        }

        $transaction = $this->repo->create($data);

        event(new BorrowingCreated($transaction), auth()->id());

        return $transaction;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $Transaction= $this->repo->update($id, $data);

        event(new BorrowingUpdateed($Transaction),auth()->id());

        return $Transaction;
    }

    public function delete($id)
    {
        $Transaction = $this->repo->findById($id);

        if (!$Transaction) {
            throw new \Exception('Borrowing not found');
        }

        $this->repo->delete($id);

        event(new BorrowingRejected($Transaction));

        return true;
    }

}
