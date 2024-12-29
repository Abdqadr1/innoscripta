<?php

use App\Services\NewsApiService;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return (new NewsApiService)->fetch();
});
