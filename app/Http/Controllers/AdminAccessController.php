<?php

namespace App\Http\Controllers;

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
}
