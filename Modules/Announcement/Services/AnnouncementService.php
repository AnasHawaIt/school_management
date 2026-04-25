<?php

namespace Modules\Announcement\Services;

use App\Services\ImageService;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementRestored;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Repositories\Interfaces\AnnouncementRepositoryInterface;

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


    public function getAnnouncementOnlyTrashed()
    {
        return $this->repo->getAnnouncementOnlyTrashed();
    }

    public function restore($id)
    {
        $author= $this->repo->restore($id);


        event(new AnnouncementRestored($author));

        return $author;
    }

    public function forceDelete($id)
    {
        $author= $this->repo->find($id);

        $author->forceDelete();

        event(new AnnouncementDeleted($author));

        return true;
    }


    public function create(array $data,$images=null)
    {
        $announcement = $this->repo->create($data);

        $this->imageService->upload($announcement, $images);

        event(new AnnouncementCreated($announcement));

        return $announcement;
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function update(int $id, array $data,$images=null): Announcement
    {
        $announcement = $this->repo->update($id, $data);

        $this->imageService->replace($announcement, $images);

        event(new AnnouncementUpdated($announcement));

        return $announcement;
    }

    public function delete(int $id): void
    {
        $announcement = $this->repo->find($id);

        $this->imageService->deleteAll($announcement);

        $this->repo->delete($id);

        event(new AnnouncementDeleted($announcement));
    }
}
