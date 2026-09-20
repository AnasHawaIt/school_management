<?php

namespace Modules\Announcement\app\Events\Broadcasts;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Announcement\app\Entities\Announcement;

class AnnouncementBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Announcement $announcement,
        public string $action
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('announcement'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'announcement.' . $this->action;
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'body' => $this->announcement->body,
            'audience' => $this->announcement->audience->value,
            'status' => $this->announcement->status->value,
            'priority' => $this->announcement->priority->value,
            'is_pinned' => $this->announcement->is_pinned,
            'scheduled_at' => $this->announcement->scheduled_at?->toISOString(),
            'published_at' => $this->announcement->published_at?->toISOString(),
            'expires_at' => $this->announcement->expires_at?->toISOString(),
        ];
    }
}
