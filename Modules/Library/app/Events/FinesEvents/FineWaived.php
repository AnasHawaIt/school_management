<?php

namespace Modules\Library\app\Events\FinesEvents;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Library\app\Entities\Fine;

class FineWaived
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Fine $fine
    ) {}
}
