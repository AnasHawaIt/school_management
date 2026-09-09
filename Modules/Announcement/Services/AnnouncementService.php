<?php

namespace Modules\Announcement\Services;

use App\Services\ImageService;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Announcement\Events\AnnouncementExpired;
use Modules\Announcement\Events\AnnouncementPublished;
use Modules\Announcement\Events\AnnouncementRestored;
use Modules\Announcement\Events\AnnouncementScheduled;
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
        $announcement = $this->repo->restore($id);

        event(new AnnouncementRestored($announcement));

        return $announcement;
    }

    public function forceDelete(int $id): bool
    {
        $announcement = $this->repo->forceDelete($id);

        event(new AnnouncementDeleted($announcement));

        return true;
    }

    public function create(array $data, $images = null)
    {
        $announcement = $this->repo->create($data);

        $this->imageService->upload($announcement, $images);

        event(new AnnouncementCreated($announcement));

        return $announcement;
    }

    public function getPublishedAnnouncements()
    {
        return $this->repo->getPublished();
    }

    public function getScheduledAnnouncements()
    {
        return $this->repo->getScheduled();
    }

    public function getPinnedAnnouncements()
    {
        return $this->repo->getPinned();
    }

    public function getExpiredAnnouncements()
    {
        return $this->repo->getExpired();
    }

    public function getAll()
    {
        return $this->repo->getAll();
    }

    public function find($id)
    {
        return $this->repo->find($id);
    }

    public function update(int $id, array $data, $images = null): Announcement {
        $announcement = $this->repo->update($id, $data);

        $this->imageService->replace($announcement, $images);

        event(new AnnouncementUpdated($announcement));

        return $announcement;
    }

    public function publish(int $id): Announcement
    {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if (!$announcement->publish()) {
            throw new \LogicException(
                'Announcement cannot be published from its current status.'
            );
        }

        event(new AnnouncementPublished($announcement));

        return $announcement;
    }

    public function schedule(int $id, \DateTimeInterface $date): Announcement {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if (!$announcement->schedule($date)) {
            throw new \LogicException(
                'Announcement cannot be scheduled from its current status.'
            );
        }

        event(new AnnouncementScheduled($announcement));

        return $announcement;
    }

    public function expire(int $id): Announcement
    {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if (!$announcement->expire()) {
            throw new \LogicException(
                'Announcement cannot be expired from its current status.'
            );
        }

        event(new AnnouncementExpired($announcement));

        return $announcement;
    }

    public function cancel(int $id): Announcement
    {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if (!$announcement->cancel()) {
            throw new \LogicException(
                'Announcement cannot be canceled from its current status.'
            );
        }

        return $announcement;
    }

    public function pin(int $id): Announcement
    {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if (!(Announcement::published()->find($announcement->id))) {
            throw new \LogicException(
                'Announcement cannot be Pinned from its current status before publishing.'
            );
        }

        $announcement->pin();

        return $announcement;
    }

    public function unpin(int $id): Announcement
    {
        $announcement = $this->repo->find($id);

        if (!$announcement) {
            abort(404, 'Announcement not found.');
        }

        if(!$announcement->is_pinned){
            throw new \LogicException(
                'Announcement cannot be Unpinned from its current status before Pinned.'
            );
        }

        $announcement->unpin();

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
