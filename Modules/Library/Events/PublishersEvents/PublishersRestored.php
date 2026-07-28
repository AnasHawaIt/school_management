<?php


namespace Modules\Library\Events\PublishersEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Publishers;

class PublishersRestored
{
    use Dispatchable, SerializesModels;

    public Publishers $publisher;

    public function __construct(Publishers $publisher,public ?int $userId = null)
    {
        $this->publisher= $publisher;
    }
}
