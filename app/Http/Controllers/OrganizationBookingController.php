<?php
namespace App\Http\Controllers;
use App\Models\{Show,Organization,OrganizationBooking};
use Illuminate\Http\Request;
class OrganizationBookingController extends Controller
{
 public function create(Show $show){$show->load('event','venue');return view('organization-bookings.create',['show'=>$show]);}
 public function store(Request $request,Show $show){$data=$request->validate(['organization_name'=>'required|string|max:190','contact_name'=>'required|string|max:120','phone'=>'required|string|max:30','email'=>'nullable|email|max:190','guest_count'=>'required|integer|min:1|max:100000','kind'=>'required|in:seminar,festival','notes'=>'nullable|string|max:3000']);$data['show_id']=$show->id;$data['user_id']=auth()->id();$data['organization_id']=Organization::where('name',$data['organization_name'])->value('id');$data['status']='pending';OrganizationBooking::create($data);return redirect()->route('organization-bookings.success')->with('success','درخواست رزرو سازمانی ثبت شد؛ کارشناسان برای هماهنگی با شما تماس می‌گیرند.');}
 public function success(){return view('organization-bookings.success');}
 public function index(){return view('admin.organization-bookings.index',['bookings'=>OrganizationBooking::with('show.event')->latest()->paginate(20)]);}
}
