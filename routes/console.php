<?php

use App\Jobs\ProcessInventoryUpdates;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ProcessInventoryUpdates)->everyThirtySeconds();