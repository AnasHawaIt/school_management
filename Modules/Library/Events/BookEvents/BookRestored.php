<?php


namespace Modules\Library\Events\BookEvents;

use Modules\Transport\Entities\Bus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookRestored
{
    use Dispatchable, SerializesModels;

    public Bus $bus;

    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }
}
