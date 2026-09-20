<?php
use Illuminate\Support\Facades\Route; use App\Http\Controllers\Api\{CatalogApiController,TicketApiController};
Route::get('/events',[CatalogApiController::class,'events']);
Route::get('/events/{event}',[CatalogApiController::class,'event']);
Route::get('/shows/{show}',[CatalogApiController::class,'show']);
Route::middleware('auth:sanctum')->post('/tickets/{ticket}/checkin',[TicketApiController::class,'checkin']);
