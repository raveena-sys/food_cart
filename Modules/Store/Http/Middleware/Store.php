<?php

namespace Modules\Store\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Store {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
         if (!empty(Auth::user()) && Auth::user()->user_type == "store" && Auth::user()->status == "active") {
            $response = $next($request);
            return $response->header('Cache-Control', 'nocache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma', 'no-cache')
                            ->header('Expires', '0');
        }

        return redirect('store');
    }

}
