<?php

use Fintech\Bell\Http\Controllers\NotificationController;
use Fintech\Bell\Http\Controllers\TemplateController;
use Fintech\Bell\Http\Controllers\TriggerController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "API" middleware group. Enjoy building your API!
|
*/
if (Config::get('fintech.bell.enabled')) {
    Route::prefix(config('fintech.bell.root_prefix', 'api/'))->middleware(['api'])->group(function () {
        Route::prefix('bell')->name('bell.')
            ->middleware(config('fintech.auth.middleware'))
            ->group(function () {

                Route::apiResource('triggers', TriggerController::class)
                    ->only(['index', 'show'])
                    ->where(['trigger' => UUID_PATTERN]);

                Route::get('triggers/sync', [TriggerController::class, 'sync'])
                    ->name('triggers.sync');

                Route::apiResource('templates', TemplateController::class);

                Route::post('templates/{template}/restore', [TemplateController::class, 'restore'])
                    ->name('templates.restore');

                Route::apiResource('notifications', NotificationController::class)
                    ->only(['index', 'show', 'destroy']);
                // DO NOT REMOVE THIS LINE//
            });
    });
}
