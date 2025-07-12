<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckForMaintenanceMode
{
    public function handle(Request $request, Closure $next)
    {
        $maintenance = DB::table('settings')->where('key', 'maintenance')->value('value') === 'on';
        Log::info('[MaintenanceMiddleware] Path: ' . $request->path() . ' | is_admin_route: ' . ($request->is('admin*') ? 'yes' : 'no') . ' | admin_auth: ' . (auth('admin')->check() ? 'yes' : 'no'));
        if ($maintenance) {
            // Allow all admin routes to bypass maintenance mode
            if ($request->is('admin*')) {
                Log::info('[MaintenanceMiddleware] Bypassing for admin route');
                return $next($request);
            }
            if (auth('admin')->check()) {
                Log::info('[MaintenanceMiddleware] Bypassing for authenticated admin');
                return $next($request);
            }
            Log::info('[MaintenanceMiddleware] Showing maintenance page');
            return response()->view('maintenance');
        }
        return $next($request);
    }
} 