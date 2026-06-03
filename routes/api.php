<?php

use App\Http\Controllers\AuthToolController;
use App\Http\Controllers\ToolController;

Route::apiResource('tools', ToolController::class)
    ->only(['index', 'store', 'destroy']);

Route::middleware('auth')->prefix('auth')->name('auth.')->group(function () {
    Route::apiResource('tools', AuthToolController::class)
        ->only(['index', 'store', 'destroy']);
});
