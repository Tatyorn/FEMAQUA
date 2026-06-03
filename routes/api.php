<?php

use App\Http\Controllers\ToolController;

Route::apiResource('tools', ToolController::class)
    ->only(['index', 'store', 'destroy']);
