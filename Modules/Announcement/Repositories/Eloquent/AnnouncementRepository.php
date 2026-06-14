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
            ->active()
            ->published()
            ->get();
    }


    public function getAll()
    {
        $query = Announcement::query();

        return $query->get();
    }

    public function find($id)
    {
        return Announcement::find($id);
    }

    public function create(array $data)
    {
        return Announcement::create($data);
    }

    public function update($id, array $data)
    {
        $announcement = $this->find($id);

        $announcement->update($data);

        return $announcement;
    }

    public function delete($id)
    {
        $announcement = $this->find($id);

        return $announcement->delete();
    }
}
