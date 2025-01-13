<?php

use Fintech\Bell\Http\Controllers\AvailableTriggerController;
use Fintech\Bell\Http\Controllers\NotificationTemplateController;
use Fintech\Bell\Http\Controllers\TriggerActionController;
use Fintech\Bell\Http\Controllers\TriggerController;
use Fintech\Bell\Http\Controllers\TriggerRecipientController;
use Fintech\Bell\Http\Controllers\TriggerVariableController;
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
                Route::get('triggers', AvailableTriggerController::class)
                    ->name('triggers.index');
                //DO NOT REMOVE THIS LINE//
            });
    });
}
