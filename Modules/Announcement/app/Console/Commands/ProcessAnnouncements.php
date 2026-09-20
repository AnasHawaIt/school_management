<?php

namespace Modules\Announcement\app\Console\Commands;

use Illuminate\Console\Command;
use Modules\Announcement\app\Entities\Announcement;
use Modules\Announcement\app\Enums\AnnouncementStatus;
use Modules\Announcement\app\Jobs\AnnouncementExpireJob;
use Modules\Announcement\app\Jobs\AnnouncementPublishJob;

class ProcessAnnouncements extends Command
{
    protected $signature = 'announcements:process';

    protected $description = 'Process scheduled and expired announcements';

    public function handle(): int
    {
        $now = now();

        // Publish scheduled announcements
        Announcement::query()
            ->where('status', AnnouncementStatus::SCHEDULED->value)
            ->whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->select('id')
            ->chunkById(100, function ($announcements) {

                foreach ($announcements as $announcement) {
                    AnnouncementPublishJob::dispatch(
                        $announcement->id
                    );
                }
            });

        // Expire published announcements
        Announcement::query()
            ->where('status', AnnouncementStatus::PUBLISHED->value)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', $now)
            ->select('id')
            ->chunkById(100, function ($announcements) {

                foreach ($announcements as $announcement) {
                    AnnouncementExpireJob::dispatch(
                        $announcement->id
                    );
                }
            });

        return self::SUCCESS;
    }
}
