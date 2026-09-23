<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query()->with('order.user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('q')) {
            $term = $request->string('q');
            $query->where(fn ($builder) => $builder->where('authority', 'like', "%{$term}%")
                ->orWhere('reference', 'like', "%{$term}%"));
        }

        return view('admin.payments.index', ['payments' => $query->paginate(25)->withQueryString()]);
    }
}
