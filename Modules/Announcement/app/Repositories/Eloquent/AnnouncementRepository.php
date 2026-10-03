<?php

namespace Modules\Announcement\Repositories\Eloquent;

use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Repositories\Interfaces\AnnouncementRepositoryInterface;

class AnnouncementRepository implements AnnouncementRepositoryInterface
{
    public function getAnnouncementOnlyTrashed()
    {
        return Announcement::onlyTrashed()->paginate(10);
    }

    public function restore($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);
        $announcement->restore();

        return $announcement;
    }

    public function forceDelete($id)
    {
        $announcement = Announcement::withTrashed()->findOrFail($id);

        $announcement->forceDelete();

        return $announcement;
    }

    public function getPublished()
    {
        return Announcement::query()
            ->published()
            ->get();
    }

    public function getScheduled()
    {
        return Announcement::query()
            ->scheduled()
            ->get();
    }

    public function getExpired()
    {
        return Announcement::query()
            ->expired()
            ->get();
    }

    public function getPinned()
    {
        return Announcement::query()
            ->Pinned()
            ->get();
    }

    public function getAll()
    {
        return Announcement::query()
            ->with('creator')
            ->latest()
            ->get();
    }

    public function find(int $id): ?Announcement
    {
        return Announcement::find($id);
    }

    public function create(array $data)
    {
        return Announcement::create($data);
    }

    public function update(int $id, array $data): Announcement
    {
        $announcement = Announcement::findOrFail($id);

        $announcement->update($data);

        return $announcement->refresh();
    }

    public function delete(int $id): bool
    {
        $announcement = Announcement::findOrFail($id);

        return $announcement->delete();
    }
}
