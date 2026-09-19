<?php


namespace Modules\Library\app\Events\PublishersEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Publishers;

class PublishersRestored
{
    use Dispatchable, SerializesModels;

    public Publishers $publisher;

    public function __construct(Publishers $publisher,public ?int $userId = null)
    {
        $this->publisher= $publisher;
    }
}
