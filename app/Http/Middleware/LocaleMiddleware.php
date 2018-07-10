<?php

namespace Veraomat\Http\Middleware;

use Closure, Session, Auth;

class LocaleMiddleware {


    public function handle($request, Closure $next)
    {
        if(Auth::user()){
        	// App::setLocale(Auth::user()->locale);
            app()->setLocale(Auth::user()->locale);
        }elseif(Session::has('locale')){
        	$locale = Session::get('locale');
            app()->setLocale($locale);
        }else{
        	app()->setLocale('pt-br');
        }

        return $next($request);
    }

}