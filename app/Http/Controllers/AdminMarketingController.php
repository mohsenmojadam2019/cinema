<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminMarketingController extends Controller
{
    public function index()
    {
        return view('admin.marketing.index', ['coupons' => Coupon::latest()->paginate(25)]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:40', 'unique:coupons,code'], 'type' => ['required', 'in:percent,fixed'], 'value' => ['required', 'integer', 'min:1'], 'usage_limit' => ['nullable', 'integer', 'min:1'], 'starts_at' => ['nullable', 'date'], 'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at']]);
        Coupon::create($data);

        return back()->with('success', 'کد تخفیف ایجاد شد.');
    }
}
