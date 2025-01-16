<?php

use App\Http\Controllers\MeterController;
use App\Http\Controllers\MeterReadingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UploadMeterReadingsController;
use Illuminate\Support\Facades\Route;

// redirecting here just so I can keep all my resource routes
Route::get('/', function () {
    return redirect()->route('meters.index');
});

Route::prefix('meters')->name('meters.')->group(function () {
    Route::get('/create', [MeterController::class, 'create'])->name('create');
    Route::get('/', [MeterController::class, 'index'])->name('index');
    Route::get('/{meter}', [MeterController::class, 'show'])->name('show');
    Route::post('/', [MeterController::class, 'store'])->name('store');

    Route::post('/{meter}/readings', [MeterReadingController::class, 'store'])->name('readings.store');

    Route::prefix('readings')->name('readings.')->group(function () {
        Route::get('/bulk-upload', [MeterReadingController::class, 'bulkUpload'])->name('bulk-upload');
        Route::post('/upload', UploadMeterReadingsController::class)->name('upload-csv');
    });
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
