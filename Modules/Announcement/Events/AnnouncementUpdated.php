<?php

namespace Modules\Announcement\Events;

use Modules\Announcement\Entities\Announcement;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AnnouncementUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Announcement $announcement,
        public ?string $socketId = null
    ) {}
}

