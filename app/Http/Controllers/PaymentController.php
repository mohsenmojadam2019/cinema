<?php
namespace App\Http\Controllers;
use App\Models\{Order,Payment,Reservation,Ticket}; use App\Jobs\SendSmsJob; use App\Services\ZarinpalService; use Illuminate\Http\Request;
class PaymentController extends Controller
{
 public function callback(Request $r,ZarinpalService $z){$authority=(string)$r->query('Authority');$payment=Payment::where('authority',$authority)->where('status','pending')->firstOrFail();if(strtolower((string)$r->query('Status'))!=='ok'||!$z->verify($payment)){$payment->order->update(['status'=>'failed']);return view('booking.failed');}$order=$payment->order->load('user');if($order->status!=='paid'){$order->update(['status'=>'paid','paid_at'=>now()]);Reservation::where('token',$order->code)->update(['status'=>'converted']);Ticket::where('order_id',$order->id)->update(['status'=>'valid']);$payment->update(['status'=>'paid']);if($order->user?->phone)SendSmsJob::dispatch($order->user->phone,'پرداخت سفارش '.$order->code.' با موفقیت انجام شد.')->onQueue('notifications');}return redirect()->route('booking.success',$order)->with('payment_ref',$authority);}
}
