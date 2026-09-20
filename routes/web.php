<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\{BookingController,AdminDashboardController,TicketController,AdminEventController,PaymentController,CatalogController};
use App\Http\Controllers\AuthController;

Route::get('/', HomeController::class)->name('home');
Route::get('/search',[CatalogController::class,'search'])->name('catalog.search');
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'show'])->name('login');Route::post('/login',[AuthController::class,'login'])->name('login.store');});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::get('/events/{event}', [BookingController::class,'event'])->name('events.show');
Route::get('/booking/{show}/seats', [BookingController::class,'seats'])->name('booking.seats');
Route::post('/booking/{show}/checkout', [BookingController::class,'checkout'])->name('booking.checkout');
Route::get('/payment/callback',[PaymentController::class,'callback'])->name('payment.callback');
Route::get('/booking/success/{order}', [BookingController::class,'success'])->name('booking.success');
Route::get('/tickets/{ticket}/qr',[TicketController::class,'qr'])->name('tickets.qr');
Route::post('/admin/tickets/{ticket}/checkin',[TicketController::class,'checkin'])->middleware(['auth','role:مدیر سیستم|اپراتور گیشه'])->name('admin.tickets.checkin');
Route::prefix('admin')->name('admin.')->middleware(['auth','role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function(){Route::get('/',AdminDashboardController::class)->name('dashboard');Route::resource('events',AdminEventController::class)->only(['index','create','store']);});
