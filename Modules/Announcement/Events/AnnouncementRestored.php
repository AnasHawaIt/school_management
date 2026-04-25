<?php

namespace Modules\Announcement\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\Entities\Announcement;
class AnnouncementRestored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Announcement $announcement,
        public ?string $socketId = null
    ) {}

}

