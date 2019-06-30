<?php
namespace App\Http\Middleware;

use Closure;

/**
 * 禁止浏览器back(后退)缓存中间件
 * @package App\Http\Middleware
 */
class RevalidateBackHistory
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
        $response = $next($request);
        //返回资源存在错误信息禁止缓存
        if(!empty(session('errors'))) {
            return $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                            ->header('Pragma','no-cache');
        } else {
            return $response;
        }
    }
}
