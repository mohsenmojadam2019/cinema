<?php
namespace App\Http\Controllers;
use App\Models\{Event,Show,Seat,Order,Ticket};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class BookingController extends Controller {
 public function event(Event $event){return view('events.show',['event'=>$event->load('category','shows.venue'),'shows'=>$event->shows()->with('venue')->where('status','on_sale')->where('starts_at','>',now())->orderBy('starts_at')->get()]);}
 public function seats(Show $show){$show->load('event','venue');$taken=Ticket::whereHas('order',fn($q)=>$q->where('show_id',$show->id)->whereIn('status',['paid','valid']))->pluck('seat_id');return view('booking.seats',['show'=>$show,'seats'=>$show->venue->seats()->where('is_active',true)->orderBy('row_label')->orderBy('number')->get(),'taken'=>$taken]);}
 public function checkout(Request $request,Show $show){$data=$request->validate(['seats'=>'required|array|min:1','seats.*'=>'integer|exists:seats,id']);$show->load('venue');$order=null;DB::transaction(function()use($data,$show,&$order){$seats=Seat::whereIn('id',$data['seats'])->where('venue_id',$show->venue_id)->lockForUpdate()->get();if($seats->count()!==count($data['seats']))abort(422,'صندلی نامعتبر است.');$busy=Ticket::whereIn('seat_id',$seats->pluck('id'))->whereHas('order',fn($q)=>$q->where('show_id',$show->id)->whereIn('status',['paid','valid']))->exists();if($busy)abort(409,'یکی از صندلی‌ها قبلاً رزرو شده است.');$order=Order::create(['show_id'=>$show->id,'code'=>'CP-'.strtoupper(Str::random(10)),'total'=>$seats->sum(fn($s)=>$s->type==='vip'&&$show->vip_price?$show->vip_price:$show->price),'status'=>'paid','paid_at'=>now()]);foreach($seats as $seat)Ticket::create(['order_id'=>$order->id,'seat_id'=>$seat->id,'code'=>'T-'.strtoupper(Str::random(12)),'status'=>'valid']);});return redirect()->route('booking.success',$order);}
 public function success(Order $order){return view('booking.success',['order'=>$order->load('show.event','show.venue','tickets.seat')]);}
}
