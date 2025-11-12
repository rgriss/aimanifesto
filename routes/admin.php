<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DataManagementController;
use App\Http\Controllers\Admin\ToolController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'verified', 'admin'])
    ->group(function () {
        // Admin Dashboard
        Route::get('/', function () {
            return Inertia::render('Admin/Index');
        })->name('index');

        // Categories CRUD
        Route::resource('categories', CategoryController::class)->except(['show']);

        // Tools CRUD
        Route::resource('tools', ToolController::class)->except(['show']);

        // Data Management (Import/Export)
        Route::get('/data', [DataManagementController::class, 'index'])->name('data.index');
        Route::post('/data/export', [DataManagementController::class, 'export'])->name('data.export');
        Route::post('/data/import', [DataManagementController::class, 'import'])->name('data.import');
        Route::get('/data/download/{filename}', [DataManagementController::class, 'download'])->name('data.download');
        Route::delete('/data/delete/{filename}', [DataManagementController::class, 'delete'])->name('data.delete');

        // User Management
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
