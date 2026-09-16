<?php

namespace Modules\Announcement\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
use Modules\Announcement\Enums\AnnouncementStatus;
use Modules\Announcement\Events\AnnouncementPublished;
use Modules\Announcement\Services\AnnouncementCache;

class AnnouncementPublishJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;
    public int $timeout = 60;
    public int $uniqueFor = 3600;

    public function __construct(
        public int $announcementId
    ) {}

    public function uniqueId(): string
    {
        return (string) $this->announcementId;
    }

    public function backoff(): array
    {
        return [30, 120];
    }

    public function handle(AnnouncementCache $cache): void
    {
        $announcement = Announcement::find($this->announcementId);

        /*
        |--------------------------------------------------------------------------
        | Announcement does not exist
        |--------------------------------------------------------------------------
        */
        if (!$announcement) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Idempotency
        |--------------------------------------------------------------------------
        |
        | If the job runs more than once, only a SCHEDULED
        | announcement can be published.
        |
        */
        if ($announcement->status !== AnnouncementStatus::SCHEDULED) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Scheduled time has not arrived yet
        |--------------------------------------------------------------------------
        */
        if (
            $announcement->scheduled_at === null ||
            $announcement->scheduled_at->isFuture()
        ) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Publish announcement
        |--------------------------------------------------------------------------
        */
        if (!$announcement->publish()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Automatic Event
        |--------------------------------------------------------------------------
        */
        event(new AnnouncementPublished($announcement));
        $cache->invalidate();
    }
}
