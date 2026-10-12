<?php


namespace Modules\School\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\School\Entities\Section;

class SectionDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Section $section,
        public ?int $createBy=null
    )
    {
    }
}
