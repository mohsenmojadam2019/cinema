<?php
namespace App\Http\Controllers;
use App\Models\{Show,Event,Venue}; use Illuminate\Http\Request;
class AdminShowController extends Controller
{
 public function index(){return view('admin.shows.index',['shows'=>Show::with('event','venue')->latest('starts_at')->paginate(20)]);}
 public function create(){return view('admin.shows.form',['show'=>new Show,'events'=>Event::where('status','published')->get(),'venues'=>Venue::all()]);}
 public function edit(Show $show){return view('admin.shows.form',['show'=>$show,'events'=>Event::where('status','published')->get(),'venues'=>Venue::all()]);}
 public function store(Request $r){$d=$r->validate(['event_id'=>'required|exists:events,id','venue_id'=>'required|exists:venues,id','starts_at'=>'required|date','ends_at'=>'nullable|date|after:starts_at','price'=>'required|integer|min:0','vip_price'=>'nullable|integer|min:0']);Show::create($d+['status'=>'on_sale']);return redirect()->route('admin.shows.index')->with('success','سانس ثبت شد.');}
 public function update(Request $r,Show $show){$d=$r->validate(['event_id'=>'required|exists:events,id','venue_id'=>'required|exists:venues,id','starts_at'=>'required|date','ends_at'=>'nullable|date|after:starts_at','price'=>'required|integer|min:0','vip_price'=>'nullable|integer|min:0','status'=>'nullable|in:on_sale,closed,cancelled']);$show->update($d);return redirect()->route('admin.shows.index')->with('success','سانس ویرایش شد.');}
 public function destroy(Show $show){$show->delete();return back()->with('success','سانس حذف شد.');}
}
