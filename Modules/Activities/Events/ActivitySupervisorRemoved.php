<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Entities\ActivitySupervisor;


class ActivitySupervisorRemoved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivitySupervisor $supervisor,
        public ?int $causedBy = null
    )
    {
    }
}
