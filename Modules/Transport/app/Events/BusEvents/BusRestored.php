<?php


namespace Modules\Transport\Events\BusEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Bus;

class BusRestored
{
    use Dispatchable, SerializesModels;

    public Bus $bus;

    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }
}
