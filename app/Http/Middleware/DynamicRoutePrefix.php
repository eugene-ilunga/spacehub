<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DynamicRoutePrefix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Set a default prefix
        $prefix = 'default';

        if ($request->has('paymentForPackage')) {
            $prefix = 'membership';
        } elseif ($request->has('paymentForFeature')) {
            $prefix = 'feature';
        }

        // Store the prefix in the request for later use
        $request->attributes->set('dynamic_prefix', $prefix);
        return $next($request);
    }
}
