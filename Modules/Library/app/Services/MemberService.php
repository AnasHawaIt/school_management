<?php


namespace Modules\Library\Services;

use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
use Modules\Library\Events\MemberEvents\MemberForceDeleted;
use Modules\Library\Events\MemberEvents\MemberRestored;
use Modules\Library\Events\MemberEvents\MemberUpdated;
use Modules\Library\Repositories\Interfaces\MemberRepositoryInterface;

class MemberService
{
    protected $repo;

    public function __construct(MemberRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getMemberOnlyTrashed()
    {
        return $this->repo->getMemberOnlyTrashed();
    }

    public function restore($id)
    {
        $member= $this->repo->restore($id);

        event(new MemberRestored($member));

        return $member;
    }

    public function forceDelete($id)
    {
        $member= $this->repo->forceDelete($id);

        event(new MemberForceDeleted($member));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $data['start_date']=now();
        $data['end_date']=now()->addYear();
        $Member= $this->repo->create($data);

        event(new MemberCreated($Member,auth()->id()));

        return $Member;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $Member= $this->repo->update($id, $data);

        event(new MemberUpdated($Member,auth()->id()));

        return $Member;
    }

    public function delete($id)
    {
        $Member=$this->repo->findById($id);

        if (!$Member) {
            throw new \Exception('Member not found');
        }

        $this->repo->delete($id);

        event(new MemberDeleted($Member));

        return true;
    }
}
