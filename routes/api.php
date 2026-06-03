<?php

use App\Http\Controllers\AuthToolController;
use App\Http\Controllers\ToolController;

Route::apiResource('tools', ToolController::class)
    ->only(['index', 'store', 'destroy']);

// rotas autenticadas
Route::prefix('auth')->apiResource('tools-auth', AuthToolController::class)
    ->only(['index', 'store', 'destroy'])
    ->names(['tools-auth.index', 'tools-auth.store', 'tools-auth.destroy'])
    ->parameters(['tools-auth' => 'tool'])
    ->middleware('auth');
