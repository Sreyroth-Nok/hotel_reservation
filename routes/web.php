<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoomTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ExportController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard Route (Accessible to all authenticated users)
|--------------------------------------------------------------------------
*/
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('/dashboard/stats', [DashboardController::class, 'getMonthlyStats'])->name('dashboard.stats')->middleware('auth');
Route::get('/dashboard/reports', [DashboardController::class, 'reports'])->name('dashboard.reports')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Guest Routes (Accessible to staff and admin)
|--------------------------------------------------------------------------
*/
Route::prefix('guests')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [GuestController::class, 'index'])->name('guests.index');
    Route::get('/create', [GuestController::class, 'create'])->name('guests.create');
    Route::post('/', [GuestController::class, 'store'])->name('guests.store');
    Route::get('/{id}', [GuestController::class, 'show'])->name('guests.show');
    Route::get('/{id}/edit', [GuestController::class, 'edit'])->name('guests.edit');
    Route::put('/{id}', [GuestController::class, 'update'])->name('guests.update');
    Route::delete('/{id}', [GuestController::class, 'destroy'])->name('guests.destroy');
});

/*
|--------------------------------------------------------------------------
| Reservation Routes (Accessible to staff and admin)
|--------------------------------------------------------------------------
*/
Route::prefix('reservations')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/{id}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/{id}/edit', [ReservationController::class, 'edit'])->name('reservations.edit');
    Route::put('/{id}', [ReservationController::class, 'update'])->name('reservations.update');
    Route::post('/{id}/check-in', [ReservationController::class, 'checkIn'])->name('reservations.check-in');
    Route::post('/{id}/check-out', [ReservationController::class, 'checkOut'])->name('reservations.check-out');
    Route::post('/{id}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('/check-availability', [ReservationController::class, 'checkAvailability'])->name('reservations.check-availability');
    Route::post('/guests', [ReservationController::class, 'storeGuest'])->name('reservations.guests.store');
});

/*
|--------------------------------------------------------------------------
| Room Routes (Accessible to staff and admin)
|--------------------------------------------------------------------------
*/
Route::prefix('rooms')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/{id}', [RoomController::class, 'show'])->name('rooms.show');
    Route::put('/{id}', [RoomController::class, 'update'])->name('rooms.update');
    Route::post('/{id}/status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
    Route::delete('/{id}', [RoomController::class, 'destroy'])->name('rooms.destroy');
    Route::get('/type/{type_id}', [RoomController::class, 'getRoomsByType'])->name('rooms.by-type');
});

/* 
|--------------------------------------------------------------------------
| Room Type Routes (Admin only)
|--------------------------------------------------------------------------
*/
Route::prefix('room-types')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [RoomTypeController::class, 'index'])->name('room-types.index');
    Route::get('/create', [RoomTypeController::class, 'create'])->name('room-types.create');
    Route::post('/', [RoomTypeController::class, 'store'])->name('room-types.store');
    Route::get('/{id}/edit', [RoomTypeController::class, 'edit'])->name('room-types.edit');
    Route::put('/{id}', [RoomTypeController::class, 'update'])->name('room-types.update');
    Route::delete('/{id}', [RoomTypeController::class, 'destroy'])->name('room-types.destroy');
});

/*
|--------------------------------------------------------------------------
| Payment Routes (Accessible to staff and admin)
|--------------------------------------------------------------------------
*/
Route::prefix('payments')->middleware(['auth', 'staff'])->group(function () {
    Route::get('/', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/create/{reservation_id}', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('/{id}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('/reservation/{reservation_id}/summary', [PaymentController::class, 'getPaymentSummary'])->name('payments.summary');
    Route::get('/{id}/receipt', [PaymentController::class, 'generateReceipt'])->name('payments.receipt');
});

/*
|--------------------------------------------------------------------------
| User Routes (Admin only)
|--------------------------------------------------------------------------
*/
Route::prefix('users')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/', [UserController::class, 'store'])->name('users.store');
    Route::get('/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('users.destroy');
});

/*
|--------------------------------------------------------------------------
| Audit Log Routes (Admin only)
|--------------------------------------------------------------------------
*/
Route::prefix('audit-logs')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::get('/{id}', [AuditLogController::class, 'show'])->name('audit-logs.show');
});

/*
|--------------------------------------------------------------------------
| Export Routes (Accessible to staff and admin)
|--------------------------------------------------------------------------
*/
Route::prefix('export')->middleware(['auth', 'staff'])->group(function () {
    // Reservations export
    Route::get('/reservations/csv', [ExportController::class, 'reservationsCsv'])->name('export.reservations.csv');
    Route::get('/reservations/pdf', [ExportController::class, 'reservationsPdf'])->name('export.reservations.pdf');
    
    // Guests export
    Route::get('/guests/csv', [ExportController::class, 'guestsCsv'])->name('export.guests.csv');
    
    // Payments export
    Route::get('/payments/csv', [ExportController::class, 'paymentsCsv'])->name('export.payments.csv');
    
    // Rooms export
    Route::get('/rooms/csv', [ExportController::class, 'roomsCsv'])->name('export.rooms.csv');
});