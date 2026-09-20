<?php
namespace App\Http\Controllers;
use App\Models\{Venue,Organization,Seat}; use Illuminate\Http\Request;
class AdminVenueController extends Controller
{
 public function index(){return view('admin.venues.index',['venues'=>Venue::withCount('seats')->latest()->paginate(15)]);}
 public function create(){return view('admin.venues.form',['venue'=>new Venue]);}
 public function edit(Venue $venue){return view('admin.venues.form',['venue'=>$venue]);}
 public function store(Request $r){$d=$this->validated($r);$org=Organization::firstOrCreate(['slug'=>'cinemaplus'],['name'=>'سینماپلاس']);$v=Venue::create(['organization_id'=>$org->id,'name'=>$d['name'],'city'=>$d['city'],'address'=>$d['address']??null,'capacity'=>$d['rows']*$d['seats_per_row']]);$this->syncSeats($v,$d);return redirect()->route('admin.venues.index')->with('success','سالن ثبت شد.');}
 public function update(Request $r,Venue $venue){$d=$this->validated($r);$venue->update(['name'=>$d['name'],'city'=>$d['city'],'address'=>$d['address']??null,'capacity'=>$d['rows']*$d['seats_per_row']]);if(!$venue->shows()->where('starts_at','>',now())->exists()){$venue->seats()->delete();$this->syncSeats($venue,$d);}return redirect()->route('admin.venues.index')->with('success','سالن ویرایش شد.');}
 private function validated(Request $r):array{return $r->validate(['name'=>'required|max:190','city'=>'required|max:100','address'=>'nullable|max:500','rows'=>'required|integer|min:1|max:30','seats_per_row'=>'required|integer|min:1|max:50']);}
 private function syncSeats(Venue $v,array $d):void{for($row=0;$row<$d['rows'];$row++)for($n=1;$n<=$d['seats_per_row'];$n++)Seat::create(['venue_id'=>$v->id,'row_label'=>chr(1575+$row),'number'=>$n,'type'=>$row===0?'vip':'standard']);}
 public function destroy(Venue $venue){$venue->delete();return back()->with('success','سالن حذف شد.');}
}
