<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RackController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/pending-approval', function () {
    return Inertia::render('Auth/PendingApproval');
})->middleware('auth')->name('pending.approval');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'approved'])->name('dashboard');

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Files
    Route::resource('files', FileController::class);
    Route::get('/files/{file}/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/files/{file}/preview', [FileController::class, 'preview'])->name('files.preview');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        // User management
        Route::resource('users', UserController::class)->only(['index', 'destroy']);
        Route::patch('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        
        // Rack management
        Route::resource('racks', RackController::class);
        Route::get('/racks/{rack}/sub-racks/create', [RackController::class, 'createSubRack'])->name('racks.sub-racks.create');
        Route::post('/racks/{rack}/sub-racks', [RackController::class, 'storeSubRack'])->name('racks.sub-racks.store');
        Route::delete('/sub-racks/{subRack}', [RackController::class, 'destroySubRack'])->name('sub-racks.destroy');
    });
});

require __DIR__.'/auth.php';

// Debug route
require __DIR__.'/debug.php';
