<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\ActivityPartner;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class Activity {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);
        return $response;
    }

}
