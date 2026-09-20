<?php
namespace App\Http\Controllers; use Illuminate\Http\Request;
class CustomerAccountController extends Controller {public function orders(Request $r){$orders=$r->user()->orders()->with('show.event','tickets.seat')->latest()->paginate(15);return view('account.orders',compact('orders'));}public function tickets(Request $r){$tickets=$r->user()->orders()->with('show.event','show.venue','tickets.seat')->get()->flatMap->tickets;return view('account.tickets',compact('tickets'));}}
