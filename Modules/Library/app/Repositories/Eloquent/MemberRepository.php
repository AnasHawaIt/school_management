<?php

namespace Modules\Library\app\Repositories\Eloquent;

use Modules\Library\app\Entities\Member;
use Modules\Library\app\Filters\MemberFilter;
use Modules\Library\app\Repositories\Interfaces\MemberRepositoryInterface;

class MemberRepository implements MemberRepositoryInterface
{
    public function getMemberOnlyTrashed()
    {
        return Member::onlyTrashed()
        ->paginate(request()->get('per_page', 10));
    }

    public function restore($id)
    {
        $member = Member::withTrashed()->findOrFail($id);

         $member->restore();

         return $member;
    }

    public function query()
    {
        return Member::query();
    }

    public function forceDelete($id)
    {
        $member = Member::withTrashed()->findOrFail($id);

        $member->forceDelete();

        return $member;
    }

    public function getAll( $request)
    {
        $query = $this->query();

        $query = (new MemberFilter($request))->apply($query);

        $query->latest();

        return$query->paginate($request->get('per_page', 10));
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
