<?php

use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiToolController;
use App\Http\Controllers\Api\ApiToolIntelligenceController;
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

// Public voting endpoint (no auth required)
// Throttle allows 100 votes per minute per IP - we encourage multiple clicks!
Route::post('/tools/{slug}/vote', [ApiToolController::class, 'vote'])
    ->middleware('throttle:100,1')
    ->name('api.tools.vote');

Route::middleware(['api.token', 'throttle:60,1'])->group(function () {
    // Tools Resource
    Route::get('/tools', [ApiToolController::class, 'index'])->name('api.tools.index');
    Route::get('/tools/{slug}', [ApiToolController::class, 'show'])->name('api.tools.show');
    Route::post('/tools', [ApiToolController::class, 'store'])->name('api.tools.store');
    Route::put('/tools/{slug}', [ApiToolController::class, 'update'])->name('api.tools.update');
    Route::patch('/tools/{slug}', [ApiToolController::class, 'update'])->name('api.tools.patch');
    Route::delete('/tools/{slug}', [ApiToolController::class, 'destroy'])->name('api.tools.destroy');

    // Categories Resource
    Route::get('/categories', [ApiCategoryController::class, 'index'])->name('api.categories.index');
    Route::get('/categories/{slug}', [ApiCategoryController::class, 'show'])->name('api.categories.show');
    Route::post('/categories', [ApiCategoryController::class, 'store'])->name('api.categories.store');
    Route::put('/categories/{slug}', [ApiCategoryController::class, 'update'])->name('api.categories.update');
    Route::patch('/categories/{slug}', [ApiCategoryController::class, 'update'])->name('api.categories.patch');
    Route::delete('/categories/{slug}', [ApiCategoryController::class, 'destroy'])->name('api.categories.destroy');

    // Tool Intelligence Resource
    Route::get('/tools/{slug}/intelligence', [ApiToolIntelligenceController::class, 'show'])->name('api.tool-intelligence.show');
    Route::put('/tools/{slug}/intelligence', [ApiToolIntelligenceController::class, 'update'])->name('api.tool-intelligence.update');
    Route::patch('/tools/{slug}/intelligence', [ApiToolIntelligenceController::class, 'update'])->name('api.tool-intelligence.patch');
});
