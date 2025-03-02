<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->middleware('auth:sanctum')->group(function () {
        Route::get('me', [UserController::class, 'me']);
        Route::apiResource('contacts', ContactController::class);
    });
