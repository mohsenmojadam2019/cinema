<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; use App\Models\{Event,Show}; use Illuminate\Http\JsonResponse;
class CatalogApiController extends Controller { public function events():JsonResponse{return response()->json(Event::with('category')->where('status','published')->latest()->paginate(20));} public function event(Event $event):JsonResponse{return response()->json($event->load('category','shows.venue'));} public function show(Show $show):JsonResponse{return response()->json($show->load('event','venue.seats'));} }
