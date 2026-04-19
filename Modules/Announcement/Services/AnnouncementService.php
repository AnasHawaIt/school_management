<?php

namespace Modules\Announcement\Services;

use App\Services\ImageService;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementRestored;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Repositories\Interfaces\AnnouncementRepositoryInterface;
use App\Models\User;

class AnnouncementService
{
    protected $repo;
    protected $imageService;

    public function __construct(
        AnnouncementRepositoryInterface $repo,
        ImageService $imageService
    ) {
        $this->repo = $repo;
        $this->imageService = $imageService;
    }


    public function getOnlyTrashed()
    {
        return $this->repo->getAnnouncementOnlyTrashed();
    }

    public function restore($id)
    {
        $author= $this->repo->restore($id);

        $users = User::select('id', 'phone')->get();

        event(new AnnouncementRestored($author,$users));

        return $author;
    }

    public function forceDelete($id)
    {
        $author= $this->repo->find($id);

        $author->forceDelete();

        $users = User::select('id', 'phone')->get();

        event(new AnnouncementDeleted($author,$users));

        return true;
    }


    public function create(array $data)
    {
        $announcement = $this->repo->create($data);

        $users = User::select('id', 'phone')->get();

        event(new AnnouncementCreated($announcement, $users));

        return $announcement;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function update(int $id, array $data): Announcement
    {
        $announcement = $this->repo->update($id, $data);

        $users = User::select('id', 'phone')->get();

        event(new AnnouncementUpdated($announcement,$users));

        return $announcement;
    }

    public function delete(int $id): void
    {
        $announcement = $this->repo->find($id);

        $this->repo->delete($id);

        $users = User::select('id', 'phone')->get();

        event(new AnnouncementDeleted($announcement, $users));
    }
}
