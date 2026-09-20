<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller; use App\Models\Ticket; use Illuminate\Http\Request;
class TicketApiController extends Controller { public function checkin(Request $r,Ticket $ticket){abort_unless($r->user()?->hasAnyRole(['مدیر سیستم','اپراتور گیشه']),403);abort_if($ticket->status!=='valid',422,'invalid_ticket');$ticket->update(['status'=>'used','checked_in_at'=>now()]);return response()->json(['ok'=>true,'ticket'=>$ticket->code]);} }
