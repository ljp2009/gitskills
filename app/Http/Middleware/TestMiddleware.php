<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Task;
use App\Common\TaskStep;
use App\Schedules\PkTaskSchedule;
class TestMiddleware
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
        $resp =  $next($request);

        return $resp;
    }
}
