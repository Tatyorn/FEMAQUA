<?php

use App\Http\Controllers\ToolController;

Route::get('tools', [ToolController::class, 'index'])->name('tools.index');
Route::post('tools', [ToolController::class, 'store'])->name('tools.store');
