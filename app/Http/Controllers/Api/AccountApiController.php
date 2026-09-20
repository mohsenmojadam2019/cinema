<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; use Illuminate\Http\{Request,JsonResponse};
class AccountApiController extends Controller { public function orders(Request $r):JsonResponse{return response()->json($r->user()->orders()->with('show.event','show.venue','tickets.seat')->latest()->paginate(20));} public function tickets(Request $r):JsonResponse{return response()->json($r->user()->orders()->with('tickets.seat','show.event')->get()->pluck('tickets')->flatten());} }
