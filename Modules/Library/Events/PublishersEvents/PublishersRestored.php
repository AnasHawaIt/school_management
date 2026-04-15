<?php


namespace Modules\Library\Events\PublishersEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Publishers;

class PublishersRestored
{
    use Dispatchable, SerializesModels;

    public Publishers $publishers;

    public function __construct(Publishers $publishers,public ?int $userId = null)
    {
        $this->publishers = $publishers;
    }
}
