<?php


namespace Modules\Activities\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\Entities\ActivityParticipant;


class ActivityParticipantAbsent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivityParticipant $participant
    )
    {
    }
}
