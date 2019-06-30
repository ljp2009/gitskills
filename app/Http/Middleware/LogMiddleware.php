<?php
namespace App\Http\Middleware;
use App\Common\CommonUtils as CU;
use App\Common\LogHandler;
use Closure,Auth;
use Config;

class LogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $resource='')
    {
        if (!$request->ajax() || $request->method == 'post') {
            LogHandler::recordSystemLog();
        }
        if($resource != ''){
            LogHandler::recordVisitLog($resource, $request->id);
        }
        return $next($request);
    //	$logController = new LogController;
    //	$result = CU::getAgent();
    // 	$user_id = Auth::check() ? Auth::user()->id : 0;
    //    if (Config::get('app.log') == 'single') {
    //        $logController->recordSqlVisitLog($result['os'],$_SERVER['REQUEST_METHOD'],$user_id, $resource, $request->id);
    //    } else {
    //         if ($request->ajax()) {
    //             $logController->recordVisitLog($result['os'],$_SERVER['REQUEST_METHOD'],$user_id);
    //         } else {
    // //             $result = CU::getAgent(); 
    //             $logController->recordVisitLog($result['os'],$_SERVER['REQUEST_METHOD'],$user_id);
    // //              $logController->getVisitLog();
    //         }
    //    }
        
        
    }
}
