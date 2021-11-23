<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class PersonalMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ((auth()->user()->role_id == User::ROLE_STUDENT) &&
            (auth()->user()->role_id == User::ROLE_TEACHER)
        ) {
            abort(404);
        }
        return $next($request);
    }
}
