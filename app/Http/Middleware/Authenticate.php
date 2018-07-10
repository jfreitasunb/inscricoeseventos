<?php

namespace Veraomat\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{

    protected $auth;

    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            if ($request->ajax()) {
                return response('Unauthorized.',410);
            }else{
                return redirect()->guest('auth.login');
            }
        }
        return $next($request);
    }
}
