<?php


namespace Modules\Transport\Events\BusEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\Entities\Bus;
use Modules\Transport\Entities\RouteStop;

class BusStopStageChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Bus       $bus,
        public RouteStop $stop,
        public string    $stage,
        public float     $distanceMeters,
        public int       $etaMinutes,
    )
    {
    }
}
