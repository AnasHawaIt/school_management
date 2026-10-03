<?php

use Illuminate\Support\Facades\Schedule;
use Modules\Library\Jobs\DetectOverdueBorrowingsJob;
use Modules\Library\Jobs\ExpireReservationsJob;



Schedule::job(new DetectOverdueBorrowingsJob())
    ->everyTenMinutes();

Schedule::job(new ExpireReservationsJob())
    ->hourly();
