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
use Modules\Announcement\Events\AnnouncementExpired;
use Modules\Announcement\Services\AnnouncementCache;

class AnnouncementExpireJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
         * الإعلان لم يعد موجودًا أو تم حذفه.
         */
        if (!$announcement) {
            return;
        }

        /*
         * لا نريد Expire لإعلان ليس Published.
         */
        if ($announcement->status !== AnnouncementStatus::PUBLISHED) {
            return;
        }

        /*
         * لا يوجد تاريخ انتهاء.
         */
        if (!$announcement->expires_at) {
            return;
        }

        /*
         * لم يحن وقت الانتهاء بعد.
         */
        if ($announcement->expires_at->isFuture()) {
            return;
        }

        /*
         * تغيير الحالة إلى Expired.
         */
        if (!$announcement->expire()) {
            return;
        }

        /*
         * إطلاق Event.
         */
        event(new AnnouncementExpired($announcement));
        $cache->invalidate();
    }
}
