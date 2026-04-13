<?php

namespace Modules\Announcement\Services;

use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AuthorUpdated;
use Modules\Announcement\Events\AuthorDeleted;
use Modules\Core\Entities\ActivityLog;
use App\Models\User;
class AnnouncementService
{
    public function create(array $data)
    {
        $announcement = Announcement::create($data);

        $users = User::select('id', 'phone')->get();

         ActivityLog::log([
            'action' => 'create',
            'model_type' => Announcement::class,
            'model_id' => $announcement->id,
            'new_values' => $announcement->toArray(),
        ]);

        event(new AnnouncementCreated($announcement, $users));


        return $announcement;
    }

    public function getAll()
    {
        return Announcement::latest()->paginate(10);
    }

    public function update(int $id, array $data): Announcement
    {
        $announcement = Announcement::findOrFail($id);

        $oldValues = $announcement->toArray();

        $users = User::select('id', 'phone')->get();

        $announcement->update($data);

        ActivityLog::log([
            'action' => 'update',
            'model_type' => Announcement::class,
            'model_id' => $announcement->id,
            'old_values' => $oldValues,
            'new_values' => $announcement->toArray(),
        ]);

        event(new AuthorUpdated($announcement, $users));

        return $announcement;
    }

    public function delete(int $id): void
    {
        $announcement = Announcement::findOrFail($id);

        $oldValues = $announcement->toArray();

        $users = User::select('id', 'phone')->get();

        $announcement->delete();

        ActivityLog::log([
            'action' => 'delete',
            'model_type' => Announcement::class,
            'model_id' => $id,
            'old_values' => $oldValues,
        ]);

        event(new AuthorDeleted($announcement, $users));

    }

}
