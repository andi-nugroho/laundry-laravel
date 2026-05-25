<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/monitoring', [BookingController::class, 'index'])->name('monitoring.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::resource('bookings', BookingController::class);
    Route::resource('customers', CustomerController::class);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::resource('services', ServiceController::class)->except(['show']);
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])->name('dashboard.kasir');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('dashboard.user');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
