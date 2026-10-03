<?php

use Illuminate\Support\Facades\Schedule;
use Modules\Library\Jobs\DetectOverdueBorrowingsJob;
use Modules\Library\Jobs\ExpireReservationsJob;

/*
|--------------------------------------------------------------------------
| Announcement
|--------------------------------------------------------------------------
*/

Schedule::command('announcements:process')
    ->everyMinute();

Schedule::job(new DetectOverdueBorrowingsJob())
    ->everyTenMinutes();

Schedule::job(new ExpireReservationsJob())
    ->hourly();
