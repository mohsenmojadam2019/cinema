<?php
namespace App\Http\Controllers;
use App\Models\{Organization,Settlement,Order}; use Illuminate\Http\Request; use Illuminate\Support\Facades\DB;
class AdminSettlementController extends Controller
{
 public function index(){return view('admin.settlements.index',['settlements'=>Settlement::with('organization')->latest()->paginate(20),'organizations'=>Organization::orderBy('name')->get()]);}
 public function store(Request $r){$d=$r->validate(['organization_id'=>'required|exists:organizations,id','period_from'=>'required|date','period_to'=>'required|date|after_or_equal:period_from','commission_percent'=>'required|numeric|min:0|max:100']);$gross=Order::where('status','paid')->whereBetween('paid_at',[$d['period_from'].' 00:00:00',$d['period_to'].' 23:59:59'])->whereHas('show.venue',fn($q)=>$q->where('organization_id',$d['organization_id']))->sum('total');$commission=(int)round($gross*((float)$d['commission_percent']/100));Settlement::create(['organization_id'=>$d['organization_id'],'period_from'=>$d['period_from'],'period_to'=>$d['period_to'],'gross_amount'=>$gross,'commission_amount'=>$commission,'net_amount'=>$gross-$commission,'status'=>'draft']);return back()->with('success','تسویه محاسبه و ثبت شد.');}
 public function markPaid(Request $r,Settlement $settlement){$d=$r->validate(['reference'=>'required|string|max:190']);$settlement->update(['status'=>'paid','reference'=>$d['reference'],'paid_at'=>now()]);return back()->with('success','تسویه پرداخت‌شده ثبت شد.');}
}
