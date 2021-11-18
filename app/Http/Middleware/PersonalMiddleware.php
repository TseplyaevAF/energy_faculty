<?php

namespace App\Http\Middleware;

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
        if (!isset(auth()->user()->role->student_id) && !isset(auth()->user()->role->teacher_id)) {
            abort(404);
        }
        return $next($request);
    }
}
