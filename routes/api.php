<?php

use App\Http\Controllers\AuthToolController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tools', ToolController::class)
    ->only(['index', 'store', 'destroy']);

Route::middleware('auth:sanctum')->prefix('auth')->name('auth.')->group(function () {
    Route::apiResource('tools', AuthToolController::class)
        ->only(['index', 'store', 'destroy']);
});
