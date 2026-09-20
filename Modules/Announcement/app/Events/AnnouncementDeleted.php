<?php

namespace Modules\Announcement\app\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\app\Entities\Announcement;

class AnnouncementDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Announcement $announcement,
        public ?string $socketId = null
    ) {}
}


