<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Entities\Activity;

class ActivityCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Activity $activity,
        public ?int $causedBy = null
    )
    {
    }
}
