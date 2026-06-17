<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'redirect'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/monitoring', [BookingController::class, 'index'])->name('monitoring.index');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');
    Route::get('/payments/{payment}/pay', [PaymentController::class, 'pay'])->name('payments.pay');
    Route::patch('/payments/{payment}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');
    Route::resource('bookings', BookingController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('payments', PaymentController::class);
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.admin');
        Route::resource('services', ServiceController::class)->except(['show']);
        Route::resource('users', UserController::class)->only(['edit', 'update']);
    });

    Route::middleware('role:kasir')->group(function () {
        Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])->name('dashboard.kasir');
        Route::get('/kasir/riwayat', [BookingController::class, 'kasirHistory'])->name('kasir.riwayat');
    });

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [DashboardController::class, 'user'])->name('dashboard.user');
        Route::get('/user/pesan-laundry', [UserOrderController::class, 'create'])->name('user.orders.create');
        Route::post('/user/pesan-laundry/checkout', [UserOrderController::class, 'checkout'])->name('user.orders.checkout');
        Route::get('/user/pesan-laundry/success/{booking}', [UserOrderController::class, 'success'])->name('user.orders.success');
        Route::get('/user/status-cucian', [BookingController::class, 'userStatus'])->name('user.status-cucian');
        Route::get('/user/riwayat', [BookingController::class, 'userHistory'])->name('user.riwayat');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
