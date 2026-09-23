<?php

namespace App\Http\Middleware;

use App\Models\AdminActivityLog;
use Closure;
use Illuminate\Http\Request;

class LogAdminActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        if ($request->user()) {
            AdminActivityLog::create(['user_id' => $request->user()->id, 'method' => $request->method(), 'path' => $request->path(), 'ip_address' => $request->ip(), 'user_agent' => substr((string) $request->userAgent(), 0, 255), 'created_at' => now()]);
        }

        return $response;
    }
}
