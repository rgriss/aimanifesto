<?php

use App\Http\Controllers\Api\ApiToolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['api.token', 'throttle:30,1'])->group(function () {
    Route::post('/tools', [ApiToolController::class, 'store'])->name('api.tools.store');
});
