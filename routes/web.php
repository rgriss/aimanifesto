<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DeveloperController;
use App\Http\Controllers\DocsController;
use App\Http\Controllers\EmergencySeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\WhyController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Emergency database seeding (local development only)
Route::post('/emergency-seed', [EmergencySeedController::class, 'seed'])
    ->middleware('throttle:5,1')
    ->name('emergency.seed');

Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool:slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/request', [App\Http\Controllers\ToolRequestController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('tools.request');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/learn', function () {
    return Inertia::render('Learn/Index');
})->name('learn.index');

Route::get('/learn/beginner', function () {
    return Inertia::render('Learn/Beginner');
})->name('learn.beginner');

Route::get('/learn/intermediate', function () {
    return Inertia::render('Learn/Intermediate');
})->name('learn.intermediate');

Route::get('/learn/advanced', function () {
    return Inertia::render('Learn/Advanced');
})->name('learn.advanced');

Route::get('/brand', [BrandController::class, 'index'])->name('brand');
Route::get('/why', [WhyController::class, 'index'])->name('why');

Route::get('/docs', [DocsController::class, 'index'])->name('docs.index');
Route::get('/docs/{slug}', [DocsController::class, 'show'])->name('docs.show');

// Developer documentation
Route::get('/developer/tool-schema', [DeveloperController::class, 'toolSchema'])->name('developer.tool-schema');

// Admin-only dashboard
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';