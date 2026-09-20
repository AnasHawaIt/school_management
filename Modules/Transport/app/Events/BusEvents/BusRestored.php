<?php


namespace Modules\Transport\app\Events\BusEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Bus;

class BusRestored
{
    use Dispatchable, SerializesModels;

    public Bus $bus;

    public function __construct(Bus $bus)
    {
        $this->bus = $bus;
    }
}
