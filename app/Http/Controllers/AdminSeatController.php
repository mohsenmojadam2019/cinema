<?php
namespace App\Http\Controllers;

use App\Models\{Seat,Venue};
use Illuminate\Http\Request;

class AdminSeatController extends Controller
{
    public function index(Venue $venue)
    {
        return view('admin.seats.index', ['venue'=>$venue, 'seats'=>$venue->seats()->orderBy('row_label')->orderBy('number')->paginate(100)]);
    }

    public function update(Request $request, Seat $seat)
    {
        $data=$request->validate(['row_label'=>'required|string|max:10','number'=>'required|integer|min:1|max:200','type'=>'required|in:standard,vip,accessible']);
        $seat->update($data);
        return back()->with('success','صندلی ویرایش شد.');
    }

    public function destroy(Seat $seat)
    {
        abort_if($seat->showSeats()->whereIn('status',['held','converted'])->exists() || $seat->tickets()->exists(),422,'صندلی دارای رزرو یا بلیت است و قابل حذف نیست.');
        $seat->delete();
        return back()->with('success','صندلی حذف شد.');
    }
}
