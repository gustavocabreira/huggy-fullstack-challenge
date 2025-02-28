<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth/{provider}')
    ->whereIn('provider', ['huggy'])
    ->group(function () {
        Route::get('redirect', [LoginController::class, 'redirectToProvider'])->name('auth.redirect');
        Route::get('callback', [LoginController::class, 'callback'])->name('auth.callback');
    });
