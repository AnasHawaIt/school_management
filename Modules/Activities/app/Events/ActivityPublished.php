<?php


namespace Modules\Activities\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\app\Entities\Activity;

class ActivityPublished
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Activity $activity,
        public ?int $causedBy = null
    )
    {
    }
}
