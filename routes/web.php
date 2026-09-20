<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\{BookingController,AdminDashboardController};

Route::get('/', HomeController::class)->name('home');
Route::get('/events/{event}', [BookingController::class,'event'])->name('events.show');
Route::get('/booking/{show}/seats', [BookingController::class,'seats'])->name('booking.seats');
Route::post('/booking/{show}/checkout', [BookingController::class,'checkout'])->name('booking.checkout');
Route::get('/booking/success/{order}', [BookingController::class,'success'])->name('booking.success');
Route::prefix('admin')->name('admin.')->group(function(){Route::get('/',AdminDashboardController::class)->name('dashboard');});
