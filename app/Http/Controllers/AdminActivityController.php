<?php

namespace App\Http\Controllers;

use App\Models\AdminActivityLog;

class AdminActivityController extends Controller
{
    public function index()
    {
        return view('admin.activity.index', ['logs' => AdminActivityLog::with('user')->latest('created_at')->paginate(40)]);
    }
}
