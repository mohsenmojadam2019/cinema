<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminAccessController extends Controller
{
    public function index()
    {
        return view('admin.access.index', [
            'roles' => Role::withCount('users', 'permissions')->orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function storeRole(Request $request)
    {
        Role::create(['name' => $request->validate(['name' => ['required', 'string', 'max:80', 'unique:roles,name']])['name'], 'guard_name' => 'web']);

        return back()->with('success', 'نقش ایجاد شد.');
    }
}
