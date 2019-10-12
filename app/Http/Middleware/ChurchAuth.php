<?php

namespace App\Http\Middleware;

use Closure;
use \App\User;
use Illuminate\Foundation\Http\Middleware\Authchurch as Middleware;

class Authchurch extends Middleware
{
    
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        if ($request->session()->has('CHURCH_SESSION') ) {
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
