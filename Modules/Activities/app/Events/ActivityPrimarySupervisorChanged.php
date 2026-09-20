<?php


namespace Modules\Activities\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\app\Entities\ActivitySupervisor;


class ActivityPrimarySupervisorChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivitySupervisor $supervisor,
        public ?int $causedBy = null
    )
    {
    }
}
