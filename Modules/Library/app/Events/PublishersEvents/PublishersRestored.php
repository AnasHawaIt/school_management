<?php


namespace Modules\Library\app\Events\PublishersEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Publisher;

class PublishersRestored
{
    use Dispatchable, SerializesModels;

    public Publisher $publisher;

    public function __construct(Publisher $publisher, public ?int $userId = null)
    {
        $this->publisher= $publisher;
    }
}
