<?php
namespace App\Http\Controllers;
use App\Models\{User,LoginCode}; use App\Jobs\SendSmsJob; use Illuminate\Http\Request; use Illuminate\Support\Facades\{Hash,Auth};
class CustomerAuthController extends Controller
{
 public function __construct(){ $this->middleware('throttle:6,1')->only(['send','verify']); }
 public function show(){return view('auth.customer-otp');}
 public function send(Request $r){$d=$r->validate(['phone'=>'required|string|min:10|max:20']);$code=(string)random_int(100000,999999);LoginCode::where('phone',$d['phone'])->whereNull('used_at')->delete();LoginCode::create(['phone'=>$d['phone'],'code_hash'=>Hash::make($code),'expires_at'=>now()->addMinutes(5)]);SendSmsJob::dispatch($d['phone'],'کد ورود سینماپلاس: '.$code)->onQueue('notifications');return back()->with('phone',$d['phone'])->with('success','کد ورود ارسال شد.');}
 public function verify(Request $r){$d=$r->validate(['phone'=>'required','code'=>'required|digits:6']);$row=LoginCode::where('phone',$d['phone'])->whereNull('used_at')->where('expires_at','>',now())->latest()->first();abort_unless($row&&$row->attempts<5,422,'کد منقضی شده است.');if(!Hash::check($d['code'],$row->code_hash)){ $row->increment('attempts');return back()->withErrors(['code'=>'کد صحیح نیست.'])->withInput();}$row->update(['used_at'=>now()]);$user=User::firstOrCreate(['phone'=>$d['phone']],['name'=>'مشتری','email'=>'customer+'.preg_replace('/\D/','',$d['phone']).'@cinemaplus.local','password'=>Hash::make(str()->random(40))]);Auth::login($user,true);$r->session()->regenerate();return redirect()->intended('/account/orders');}
}
