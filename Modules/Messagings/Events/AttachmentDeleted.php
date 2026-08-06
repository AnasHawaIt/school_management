<?php

namespace Modules\Messagings\Events;

use App\Models\Images;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentDeleted
{
    use Dispatchable, SerializesModels;

    public Images $image;

    public function __construct(Images $image)
    {
        $this->image = $image;
    }
}
