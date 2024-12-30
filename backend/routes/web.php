<?php

use App\Services\NewsApiService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return (new NewsApiService)->fetch('business', Carbon::today()->format('Y-m-d'),1);
});
