<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\Api\{CatalogApiController,TicketApiController,AuthApiController};
Route::post('/auth/login',[AuthApiController::class,'login']);
Route::middleware('auth:sanctum')->group(function(){Route::get('/auth/me',[AuthApiController::class,'me']);Route::post('/auth/logout',[AuthApiController::class,'logout']);});
Route::get('/events',[CatalogApiController::class,'events']);
Route::get('/events/{event}',[CatalogApiController::class,'event']);
Route::get('/shows/{show}',[CatalogApiController::class,'show']);
Route::middleware('auth:sanctum')->post('/tickets/{ticket}/checkin',[TicketApiController::class,'checkin']);
