<?php

use App\Http\Controllers\Admin\DataManagementController;
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

        // Data Management (Import/Export)
        Route::get('/data', [DataManagementController::class, 'index'])->name('data.index');
        Route::post('/data/export', [DataManagementController::class, 'export'])->name('data.export');
        Route::post('/data/import', [DataManagementController::class, 'import'])->name('data.import');
        Route::get('/data/download/{filename}', [DataManagementController::class, 'download'])->name('data.download');
        Route::delete('/data/delete/{filename}', [DataManagementController::class, 'delete'])->name('data.delete');
    });
