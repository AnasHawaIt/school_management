<?php

namespace Modules\Library\Services;

use Modules\Library\Entities\Member;
use Modules\Library\Events\TransactionEvents\TransactionCreated;
use Modules\Library\Events\TransactionEvents\TransactionDeleted;
use Modules\Library\Events\TransactionEvents\TransactionRestored;
use Modules\Library\Events\TransactionEvents\TransactionUpdated;
use Modules\Library\Repositories\Interfaces\TransactionRepositoryInterface;

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

        event(new TransactionRestored($transaction));

        return $transaction;
    }

    public function forceDelete($id)
    {
        $transaction= $this->repo->forceDelete($id);

        event(new TransactionDeleted($transaction));

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

        event(new TransactionCreated($transaction), auth()->id());

        return $transaction;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $Transaction= $this->repo->update($id, $data);

        event(new TransactionUpdated($Transaction),auth()->id());

        return $Transaction;
    }

    public function delete($id)
    {
        $Transaction = $this->repo->findById($id);

        if (!$Transaction) {
            throw new \Exception('Transaction not found');
        }

        $this->repo->delete($id);

        event(new TransactionDeleted($Transaction));

        return true;
    }

}
