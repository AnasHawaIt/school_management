<?php

namespace Modules\Announcement\Services;

use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Events\AnnouncementCreated;
use Modules\Announcement\Events\AnnouncementUpdated;
use Modules\Announcement\Events\AnnouncementDeleted;
use Modules\Core\Entities\ActivityLog;
use App\Models\User;
class AnnouncementService
{
    public function create(array $data)
    {
        $announcement = Announcement::create($data);

        // جلب أرقام المستخدمين فقط
        $phones = User::pluck('phone')->toArray();

        ActivityLog::log([
            'action' => 'create',
            'model_type' => Announcement::class,
            'model_id' => $announcement->id,
            'new_values' => $announcement->toArray(),
        ]);

        event(new AnnouncementCreated($announcement, $phones));

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

        $phones = User::pluck('phone')->toArray();

        $announcement->update($data);

        ActivityLog::log([
            'action' => 'update',
            'model_type' => Announcement::class,
            'model_id' => $announcement->id,
            'old_values' => $oldValues,
            'new_values' => $announcement->toArray(),
        ]);

        event(new AnnouncementUpdated($announcement,$phones));

        return $announcement;
    }

    public function delete(int $id): void
    {
        $announcement = Announcement::findOrFail($id);

        $oldValues = $announcement->toArray();

        $phones = User::pluck('phone')->toArray();

        $announcement->delete();

        ActivityLog::log([
            'action' => 'delete',
            'model_type' => Announcement::class,
            'model_id' => $id,
            'old_values' => $oldValues,
        ]);

        event(new AnnouncementDeleted($announcement,$phones));
    }
    
}
