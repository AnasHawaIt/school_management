<?php

namespace Modules\Announcement\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\app\Entities\Announcement;
use Modules\Announcement\app\Enums\AnnouncementStatus;
use Modules\Announcement\app\Events\AnnouncementPublished;

class AnnouncementPublishJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public int $announcementId
    ) {}

    public function handle(): void
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
    }
}
