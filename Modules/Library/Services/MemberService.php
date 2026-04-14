<?php


namespace Modules\Library\Services;

use Modules\Library\Events\MemberEvents\MemberCreated;
use Modules\Library\Events\MemberEvents\MemberDeleted;
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
        $bus= $this->repo->restore($id);

        event(new MemberRestored($bus));

        return $bus;
    }

    public function forceDelete($id)
    {
        $bus= $this->repo->forceDelete($id);

        event(new MemberDeleted($bus));

        return true;
    }

    public function getAll($request)
    {
        return $this->repo->getAll($request);
    }

    public function create(array $data)
    {
        $category= $this->repo->create($data);

        event(new MemberCreated($category),auth()->id());

        return $category;
    }

    public function findById($id)
    {
        return $this->repo->findById($id);
    }

    public function update($id, array $data)
    {
        $category= $this->repo->update($id, $data);

        event(new MemberUpdated($category),auth()->id());

        return $category;
    }

    public function delete($id)
    {
        $category = $this->repo->findById($id);

        if (!$category) {
            throw new \Exception('Bus not found');
        }

        $this->repo->delete($id);

        event(new MemberDeleted($category));

        return true;
    }
}
