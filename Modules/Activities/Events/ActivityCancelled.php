<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Entities\Activity;

class ActivityCancelled
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Activity $activity
    )
    {
    }
}
