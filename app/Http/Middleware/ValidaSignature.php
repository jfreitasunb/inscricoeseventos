<?php

namespace InscricoesEventos\Http\Middleware;

use Closure;

class ValidaSignature
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $urlIsSigned = app('url-signer')->validate($request->fullUrl());

        if (! $urlIsSigned) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
