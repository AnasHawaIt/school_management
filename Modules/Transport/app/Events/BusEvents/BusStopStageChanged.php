<?php


namespace Modules\Transport\app\Events\BusEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Transport\app\Entities\Bus;
use Modules\Transport\app\Entities\RouteStop;

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
