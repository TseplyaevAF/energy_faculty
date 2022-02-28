<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $role_id = auth()->user()->role_id;
        if ($role_id == User::ROLE_EMPLOYEE) {
            return $next($request);
        }
        abort(404);
    }
}
