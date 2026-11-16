<?php
use Illuminate\Support\Facades\Schedule;
Schedule::command('exception-center:aggregate')->everyMinute()->withoutOverlapping();
Schedule::command('exception-center:notify')->everyMinute()->withoutOverlapping();
Schedule::command('exception-center:dispatch')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('exception-center:retry')->everyFiveMinutes()->withoutOverlapping();
Schedule::command('exception-center:health-check')->hourly()->withoutOverlapping();
Schedule::command('exception-center:prune')->daily()->withoutOverlapping();
