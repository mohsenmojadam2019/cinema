<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\Request;

class AdminSupportController extends Controller
{
    public function index(Request $request)
    {
        $query = SupportTicket::with('user')->latest();
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return view('admin.support.index', ['tickets' => $query->paginate(25)->withQueryString()]);
    }
}
