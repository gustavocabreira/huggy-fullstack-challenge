<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('send:welcome-notification')->everyThirtyMinutes();
