<?php

namespace Modules\Announcement\app\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\app\Entities\Announcement;
use Modules\Announcement\app\Enums\AnnouncementStatus;
use Modules\Announcement\app\Events\AnnouncementExpired;

class AnnouncementExpireJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $announcementId
    ) {}

    public function handle(): void
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
    }
}
