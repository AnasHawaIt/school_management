<?php
namespace Modules\Transport\Repositories\Eloquent;

use Modules\Transport\Entities\Subscription;
use Modules\Transport\Repositories\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    public function create(array $data)
    {
        return Subscription::create($data);
    }

    public function find($id)
    {
        return Subscription::findOrFail($id);
    }

    public function getByStudent($studentId)
    {
        return Subscription::where('student_id', $studentId)->get();
    }

    public function update($id, array $data)
    {
        $subscription = $this->find($id);
        $subscription->update($data);

        return $subscription;
    }
}
