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

    public function update(Request $request, SupportTicket $ticket)
    {
        $ticket->update($request->validate(['status' => ['required', 'in:new,open,pending,resolved,closed'], 'priority' => ['required', 'in:low,normal,high,urgent']]));

        return back()->with('success', 'تیکت به‌روزرسانی شد.');
    }
}
