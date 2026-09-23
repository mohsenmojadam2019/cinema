<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;

class AdminOperationsController extends Controller
{
    public function index(Request $request)
    {
        $shows = Show::with(['event', 'venue'])->withCount([
            'orders as paid_orders' => fn ($query) => $query->where('status', 'paid'),
        ])->where('starts_at', '>=', now()->subHours(4))->orderBy('starts_at')->paginate(20);

        return view('admin.operations.index', compact('shows'));
    }
}
