<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Log activity for authenticated users on data-modifying requests
        if (Auth::check() && in_array(strtolower($request->getMethod()), ['post', 'put', 'patch', 'delete'])) {
            try {
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => $request->method(),
                    'path' => $request->path(),
                    'method' => $request->getMethod(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'description' => $this->getActionDescription($request),
                ]);
            } catch (\Exception $e) {
                // Silently fail if logging fails
            }
        }

        return $response;
    }

    /**
     * Get a descriptive string for the action.
     */
    private function getActionDescription(Request $request): string
    {
        $route = $request->route();
        if ($route) {
            return "{$route->getName()} - {$request->method()}";
        }
        return "{$request->path()} - {$request->method()}";
    }
}
