<?php

namespace App\Http\Middleware;

use Closure;
use \App\User;
class Auth
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
        
        if ($request->session()->has('ADMIN_SESSION') ) {
            return $next($request);
        }else{
            return redirect('login');
        }
    }
}
