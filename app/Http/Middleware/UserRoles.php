<?php

namespace Veraomat\Http\Middleware;

use Auth;
use Closure;

class UserRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        
        $libera_acesso = 0;
        
        if (Auth::check()){
            foreach ($roles as $role ) {
                if (Auth::user()->user_type == $role) {
                    $libera_acesso = 1;
                }
            }
        }else{
            return redirect('/');
        }
        
        if (!$libera_acesso) {
            return redirect('/');
        }
        
        return $next($request);
    }
}
