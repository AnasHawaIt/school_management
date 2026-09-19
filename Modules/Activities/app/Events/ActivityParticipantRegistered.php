<?php


namespace Modules\Activities\app\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Activities\app\Entities\ActivityParticipant;


class ActivityParticipantRegistered
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public ActivityParticipant $participant,
        public ?int $causedBy = null
    )
    {
    }
}
