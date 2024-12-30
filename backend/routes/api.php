<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserSettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/token', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

Route::middleware('auth:sanctum')->group(function(){


    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/user/preference/toggle', [UserController::class, 'toggle']);

    Route::get('/user/preferences', [UserController::class, 'getPreferences']);
    Route::post('/user/preferences', [UserController::class, 'setPreferences']);

    Route::get('/news_feed', [ArticleController::class, 'news_feed']);
    Route::get('/articles/search', [ArticleController::class, 'filter']);

});
