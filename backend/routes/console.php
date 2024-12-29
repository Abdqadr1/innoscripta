<?php

use App\Services\NewsApiService;
use App\Services\NewYorkTimesService;
use App\Services\TheGuardianService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Concurrency;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


Artisan::command('test-api', function () {
    
    Concurrency::run([
        fn() => (new NewsApiService)(),
        fn() => (new NewYorkTimesService)(),
        fn() => (new TheGuardianService)(),
    ]);


})->purpose('');
