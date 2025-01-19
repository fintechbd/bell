<?php

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

                Route::apiResource('triggers', \Fintech\Bell\Http\Controllers\TriggerController::class)
                    ->only(['index', 'show'])->where(['trigger' => 'uuid']);

                Route::get('triggers/sync', [\Fintech\Bell\Http\Controllers\TriggerController::class, 'sync'])
                    ->name('triggers.sync');

                Route::apiResource('templates', \Fintech\Bell\Http\Controllers\TemplateController::class);

                Route::post('templates/{template}/restore', [\Fintech\Bell\Http\Controllers\TemplateController::class, 'restore'])
                    ->name('templates.restore');

                // DO NOT REMOVE THIS LINE//
            });
    });
}
