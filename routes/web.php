<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\{BookingController,AdminDashboardController,TicketController,AdminEventController,PaymentController,CatalogController,AdminVenueController,AdminShowController};
use App\Http\Controllers\{CustomerAuthController,CustomerAccountController};
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AuthController;

Route::get('/', HomeController::class)->name('home');
Route::get('/search',[CatalogController::class,'search'])->name('catalog.search');
Route::middleware('guest')->group(function(){Route::get('/login',[AuthController::class,'show'])->name('login');Route::post('/login',[AuthController::class,'login'])->name('login.store');});
Route::post('/logout',[AuthController::class,'logout'])->middleware('auth')->name('logout');
Route::get('/customer/login',[CustomerAuthController::class,'show'])->name('customer.login');Route::post('/customer/login/send',[CustomerAuthController::class,'send'])->name('customer.login.send');Route::post('/customer/login/verify',[CustomerAuthController::class,'verify'])->name('customer.login.verify');
Route::middleware('auth')->prefix('account')->name('account.')->group(function(){Route::get('/orders',[CustomerAccountController::class,'orders'])->name('orders');Route::get('/tickets',[CustomerAccountController::class,'tickets'])->name('tickets');});
Route::get('/events/{event}', [BookingController::class,'event'])->name('events.show');
Route::get('/booking/{show}/seats', [BookingController::class,'seats'])->name('booking.seats');
Route::post('/booking/{show}/checkout', [BookingController::class,'checkout'])->name('booking.checkout');
Route::get('/payment/callback',[PaymentController::class,'callback'])->name('payment.callback');
Route::get('/booking/success/{order}', [BookingController::class,'success'])->name('booking.success');
Route::get('/tickets/{ticket}/qr',[TicketController::class,'qr'])->middleware('auth')->name('tickets.qr');
Route::post('/admin/tickets/{ticket}/checkin',[TicketController::class,'checkin'])->middleware(['auth','role:مدیر سیستم|اپراتور گیشه'])->name('admin.tickets.checkin');
Route::prefix('admin')->name('admin.')->middleware(['auth','role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function(){Route::get('/',AdminDashboardController::class)->name('dashboard');Route::resource('events',AdminEventController::class)->only(['index','create','store']);Route::resource('venues',AdminVenueController::class)->only(['index','create','store','destroy']);Route::resource('shows',AdminShowController::class)->only(['index','create','store','destroy']);Route::get('/reports/sales',[AdminReportController::class,'sales'])->name('reports.sales');Route::get('/orders',[AdminReportController::class,'orders'])->name('orders.index');Route::post('/orders/{order}/refund',[AdminReportController::class,'refund'])->name('orders.refund');});
