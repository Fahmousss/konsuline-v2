<?php

use Illuminate\Support\Facades\Schedule;

Schedule::job(new \App\Jobs\AutoCompleteMessageJob())
    ->everyMinute();
