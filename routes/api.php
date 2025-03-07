<?php

use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\TwilioController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WebhookController;
use App\Http\Controllers\Api\WebhookLogController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::name('api.')
    ->middleware('auth:sanctum')->group(function () {
        Route::get('me', [UserController::class, 'me'])->name('me');
        Route::apiResource('contacts', ContactController::class);

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('grouped-by-state', [ReportController::class, 'groupedByState'])->name('grouped-by-state');
            Route::get('grouped-by-city', [ReportController::class, 'groupedByCity'])->name('grouped-by-city');
        });

        Route::get('/twilio/generate-token', [TwilioController::class, 'generateToken']);

        Route::get('webhooks', [WebhookLogController::class, 'index']);
    });

Route::post('twilio-webhook', [TwilioController::class, 'handleTwilioWebhook'])->name('twilio.webhook');
Route::post('webhook', WebhookController::class)->name('webhook');

Route::name('api.auth.')
    ->prefix('auth')
    ->group(function () {
        Route::post('register', [LoginController::class, 'register'])->name('register');
        Route::post('login', [LoginController::class, 'login'])->name('login');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });
