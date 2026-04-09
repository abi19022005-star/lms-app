<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;

class ActivityLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (\Illuminate\Support\Facades\Auth::check()) {

            // 🔥 FILTER (hindari log tidak penting)
            if (
                $request->is('storage/*') ||
                $request->is('images/*') ||
                $request->is('css/*') ||
                $request->is('js/*') ||
                $request->ajax()
            ) {
                return $response;
            }

            logActivity([
                'action' => 'access',
                'action_type' => 'READ',
                'module' => 'system',
                'description' => 'Akses halaman: ' . $request->path(),
            ]);
        }

        return $response;
    }
}
