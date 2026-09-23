<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->withCount('orders')->latest();

        if ($request->filled('q')) {
            $term = $request->string('q');
            $query->where(fn ($builder) => $builder->where('name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%"));
        }

        return view('admin.customers.index', ['users' => $query->paginate(25)->withQueryString()]);
    }
}
