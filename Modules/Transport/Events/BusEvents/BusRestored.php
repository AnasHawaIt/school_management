<?php


namespace Modules\Transport\Events\BusEvents;

use Modules\Transport\Entities\Bus;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BusRestored
{
    use Dispatchable, SerializesModels;

    public Bus $bus;

    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }
}
