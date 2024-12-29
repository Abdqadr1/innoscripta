<?php

use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {

        $schedule->call(new NewsApiService)
            ->dailyAt('00:30')->evenInMaintenanceMode();

        $schedule->call(new NewYorkTimesService)
            ->dailyAt('00:45')->evenInMaintenanceMode();

        $schedule->call(new TheGuardianService)
            ->dailyAt('10:45')->evenInMaintenanceMode();
    })
    ->create();
