<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::view('admin/dashboard', 'dashboard')->name('admin.dashboard'); // Reuse dashboard view for now
    });

    Route::middleware('role:organizer')->group(function () {
        Route::view('organizer/dashboard', 'dashboard')->name('organizer.dashboard'); // Reuse dashboard view for now
    });
});

require __DIR__.'/settings.php';
