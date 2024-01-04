<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;


class json
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     // return $next($request)->headers->set("Content-Type","application/json");
    //     return $next($request)->headers->set('Accept','application/json');
    // }
}
