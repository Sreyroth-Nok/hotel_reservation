<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/stats', [DashboardController::class, 'getMonthlyStats'])->name('dashboard.stats');

// Reservations
Route::prefix('reservations')->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/{id}/check-in', [ReservationController::class, 'checkIn'])->name('reservations.check-in');
    Route::post('/{id}/check-out', [ReservationController::class, 'checkOut'])->name('reservations.check-out');
    Route::post('/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');
});

// Rooms
Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::post('/{id}/status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
    Route::delete('/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::get('/type/{type_id}', [RoomController::class, 'getRoomsByType'])->name('rooms.by-type');
});

// Payments
Route::prefix('payments')->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create/{reservation_id}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/reservation/{reservation_id}/summary', [PaymentController::class, 'getPaymentSummary'])->name('payments.summary');
    Route::get('/{id}/receipt', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');
});

// Users (Admin only)
Route::prefix('users')->middleware('admin')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});