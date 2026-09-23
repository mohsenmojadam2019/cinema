<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminLoyaltyController extends Controller
{
    public function index()
    {
        return view('admin.loyalty.index', ['users' => User::query()->where('loyalty_points', '>', 0)->orderByDesc('loyalty_points')->paginate(25)]);
    }
}
