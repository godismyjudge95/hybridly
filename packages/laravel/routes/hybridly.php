<?php

use Hybridly\Tables\Http\Controllers\HybridEndpointController;
use Illuminate\Support\Facades\Route;

if (config('hybridly.endpoint_enabled', true)) {
    Route::post(config('hybridly.endpoint_path', 'hybridly'), HybridEndpointController::class)
        ->name('hybridly.endpoint');
}
