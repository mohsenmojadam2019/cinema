<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = trim((string) $request->query('q'));
        abort_if($term === '', 404);

        return view('admin.search.index', ['term' => $term, 'events' => Event::where('title', 'like', "%{$term}%")->limit(8)->get(), 'users' => User::where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%")->limit(8)->get(), 'orders' => Order::where('code', 'like', "%{$term}%")->with('user')->limit(8)->get()]);
    }
}
