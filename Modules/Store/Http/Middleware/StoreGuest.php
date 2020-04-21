<?php

namespace Modules\Store\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class StoreGuest {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
      
        if (!empty(Auth::user()) && (Auth::user()->user_type == "store") && Auth::user()->status == "active") {
                return redirect('/store/dashboard');
        }

        /*if (!empty(Auth::user()) && (Auth::user()->user_type == "lender"||Auth::user()->user_type == "appraiser")) {
                return redirect('/'.Auth::user()->user_type);
        }*/
        return $next($request);
    }

}
