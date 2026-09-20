<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\Api\{CatalogApiController,TicketApiController,AuthApiController,AccountApiController,BookingApiController};
Route::post('/auth/login',[AuthApiController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){Route::get('/auth/me',[AuthApiController::class,'me']);Route::post('/auth/logout',[AuthApiController::class,'logout']);Route::get('/account/orders',[AccountApiController::class,'orders']);Route::get('/account/tickets',[AccountApiController::class,'tickets']);Route::post('/shows/{show}/checkout',[BookingApiController::class,'checkout']);});
Route::get('/events',[CatalogApiController::class,'events']);
Route::get('/events/{event}',[CatalogApiController::class,'event']);
Route::get('/shows/{show}',[CatalogApiController::class,'show']);
Route::middleware('auth:sanctum')->post('/tickets/{ticket}/checkin',[TicketApiController::class,'checkin']);
