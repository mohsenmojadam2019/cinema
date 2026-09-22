<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminSeatController;
use App\Http\Controllers\AdminSettlementController;
use App\Http\Controllers\AdminShowController;
use App\Http\Controllers\AdminVenueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CustomerAccountController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrganizationBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:مدیر سیستم|مدیر فروش'])->group(function () {
    Route::get('/admin/settlements', [AdminSettlementController::class, 'index'])->name('admin.settlements.index');
    Route::post('/admin/settlements', [AdminSettlementController::class, 'store'])->name('admin.settlements.store');
    Route::post('/admin/settlements/{settlement}/paid', [AdminSettlementController::class, 'markPaid'])->name('admin.settlements.paid');
});
Route::post('/admin/organization-bookings/{booking}', [OrganizationBookingController::class, 'update'])->middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->name('admin.organization-bookings.update');
Route::middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function () {
    Route::get('/admin/events/{event}/edit', [AdminEventController::class, 'edit'])->name('admin.events.edit');
    Route::put('/admin/events/{event}', [AdminEventController::class, 'update'])->name('admin.events.update');
});
Route::middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function () {
    Route::get('/admin/venues/{venue}/edit', [AdminVenueController::class, 'edit'])->name('admin.venues.edit');
    Route::put('/admin/venues/{venue}', [AdminVenueController::class, 'update'])->name('admin.venues.update');
});
Route::middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function () {
    Route::get('/admin/shows/{show}/edit', [AdminShowController::class, 'edit'])->name('admin.shows.edit');
    Route::put('/admin/shows/{show}', [AdminShowController::class, 'update'])->name('admin.shows.update');
});
Route::get('/admin/organization-bookings', [OrganizationBookingController::class, 'index'])->middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->name('admin.organization-bookings.index');
Route::get('/health', HealthController::class)->name('health');

Route::get('/', HomeController::class)->name('home');
Route::get('/search', [CatalogController::class, 'search'])->name('catalog.search');
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'show'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::middleware('guest')->group(function () {
    Route::get('/customer/login', [CustomerAuthController::class, 'show'])->name('customer.login');
    Route::post('/customer/login/password', [CustomerAuthController::class, 'loginWithPassword'])->name('customer.login.password');
    Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
    Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.store');
    Route::post('/customer/login/send', [CustomerAuthController::class, 'send'])->name('customer.login.send');
    Route::post('/customer/login/verify', [CustomerAuthController::class, 'verify'])->name('customer.login.verify');
});
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/orders', [CustomerAccountController::class, 'orders'])->name('orders');
    Route::get('/tickets', [CustomerAccountController::class, 'tickets'])->name('tickets');
});
Route::get('/events/{event}', [BookingController::class, 'event'])->name('events.show');
Route::get('/booking/{show}/seats', [BookingController::class, 'seats'])->name('booking.seats');
Route::get('/organization-bookings/success', [OrganizationBookingController::class, 'success'])->name('organization-bookings.success');
Route::middleware('auth')->group(function () {
    Route::get('/organization-bookings/{show}', [OrganizationBookingController::class, 'create'])->name('organization-bookings.create');
    Route::post('/organization-bookings/{show}', [OrganizationBookingController::class, 'store'])->name('organization-bookings.store');
});
Route::post('/booking/{show}/checkout', [BookingController::class, 'checkout'])->name('booking.checkout');
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::get('/booking/success/{order}', [BookingController::class, 'success'])->name('booking.success');
Route::get('/tickets/{ticket}/qr', [TicketController::class, 'qr'])->middleware('auth')->name('tickets.qr');
Route::post('/admin/tickets/{ticket}/checkin', [TicketController::class, 'checkin'])->middleware(['auth', 'role:مدیر سیستم|اپراتور گیشه'])->name('admin.tickets.checkin');
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:مدیر سیستم|مدیر فروش|اپراتور گیشه'])->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::resource('events', AdminEventController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('venues', AdminVenueController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('shows', AdminShowController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('/venues/{venue}/seats', [AdminSeatController::class, 'index'])->name('seats.index');
    Route::put('/seats/{seat}', [AdminSeatController::class, 'update'])->name('seats.update');
    Route::delete('/seats/{seat}', [AdminSeatController::class, 'destroy'])->name('seats.destroy');
    Route::get('/reports/sales', [AdminReportController::class, 'sales'])->name('reports.sales');
    Route::get('/orders', [AdminReportController::class, 'orders'])->name('orders.index');
    Route::post('/orders/{order}/cancel', [AdminReportController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{order}/refund',[AdminReportController::class, 'refund'])->name('orders.refund');
});
