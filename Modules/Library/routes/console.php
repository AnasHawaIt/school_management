<?php

use Illuminate\Support\Facades\Schedule;
use Modules\Library\app\Jobs\DetectOverdueBorrowingsJob;
use Modules\Library\app\Jobs\ExpireReservationsJob;



Schedule::job(new DetectOverdueBorrowingsJob())
    ->everyTenMinutes();

Schedule::job(new ExpireReservationsJob())
    ->hourly();
