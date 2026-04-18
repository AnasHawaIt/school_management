<?php

namespace Modules\Library\Repositories\Eloquent;

use Modules\Library\Entities\Member;
use Modules\Library\Filters\MemberFilter;
use Modules\Library\Repositories\Interfaces\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{
    public function getMemberOnlyTrashed()
    {
        $query = Member::onlyTrashed()->get();

        return $query->paginate($query->get('per_page', 10));
    }

    public function restore($id)
    {
        $member = Member::withTrashed()->findOrFail($id);

         $member->restore();

         return $member;
    }

    public function forceDelete($id)
    {
        $member = Member::withTrashed()->findOrFail($id);

        $member->forceDelete();

        return $member;
    }

    public function getAll($request)
    {
        $query = Member::query();

        $query = (new MemberFilter($request))->apply($query);

        return $query
            ->latest()
            ->paginate($request->get('per_page', 10));
    }

    public function findById($id)
    {
        return Member::findOrFail($id);
    }

    public function create(array $data)
    {
        return Member::create($data);
    }

    public function update($id, array $data)
    {
        $member = $this->findById($id);
        $member->update($data);
        return $member;
    }

    public function delete($id)
    {
        $member = $this->findById($id);
        return $member->delete();
    }
}
