<?php
namespace App\Http\Controllers;
use App\Models\{Event,Venue,Order,Ticket,Show};
class AdminDashboardController extends Controller { public function __invoke(){return view('admin.dashboard',['stats'=>['sales'=>Order::where('status','paid')->sum('total'),'tickets'=>Ticket::count(),'events'=>Event::where('status','published')->count(),'venues'=>Venue::count(),'orders'=>Order::latest()->take(8)->with('show.event')->get()]]);} }
