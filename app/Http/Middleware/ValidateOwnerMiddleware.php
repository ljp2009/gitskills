<?php

namespace App\Http\Middleware;

use Closure;
use DB, Auth;
class ValidateOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $setName){
        $set = $this->getSet($setName);
        if($set != null && !$this->checkOwner($set, $request)){
            return redirect('/noauthority');
        }
        return $next($request);
    }
    private function checkOwner($set, $request){
        $id = isset($request->$set['input'])?$request->$set['input'] :$request->input($set['input']);
        if(empty($id) || $id == 0){
            return false;
        }
        $obj = DB::table($set['table'])->where('id', $id)->select($set['userField'])->first();
        if($obj == null) return false;
        return (Auth::check() && $obj->$set['userField'] == Auth::id());
    }
    private function getSet($setName){
        $setArr = [
            'task'=>[
                'table'=>'t_task',
                'userField'=>'user_id',
                'input'=>'taskid',
            ],
            'milestone' => 'task'
        ];
        if(array_key_exists($setName, $setArr)){
            $set = $setArr[$setName];
            while(is_string($set)){
                if(array_key_exists($set, $setArr)){
                    $set = $setArr[$set];
                }else{
                    return null;
                }
            }
            return $set;
        }else{
            return null;
        }
    }
}
