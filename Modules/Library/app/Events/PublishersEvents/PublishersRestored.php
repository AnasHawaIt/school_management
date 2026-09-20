<?php


namespace Modules\Library\Events\PublishersEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Publisher;

class PublishersRestored
{
    use Dispatchable, SerializesModels;

    public Publisher $publisher;

    public function __construct(Publisher $publisher, public ?int $userId = null)
    {
        $this->publisher= $publisher;
    }
}
