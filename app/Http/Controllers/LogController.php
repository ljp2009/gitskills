<?php
/**
 * 访问日志控制器
 * @author ViewsTap xiaocui Programmer
 * @date 20160503
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Commands\EvaluationDelivery;
use App\Common\CommonUtils as CU;
use App\Models\Task;
use App\Models\TaskDelivery;
use App\Models\User;
use Auth, Redirect, Input,DB,Redis1;
use App\Models\VisitLog;
class LogController extends Controller
{
	public function conn(){
		$redis = Redis1::connection();
		return $redis;
	}
	
	public function recordVisitLog($mobileType,$action,$user_id=0,$page=''){
		$redis = $this->conn();
		$param['page'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$param['ip'] = CU::getIp();
		$param['mobile'] = $mobileType;
		$param['userID'] = $user_id;
		$param['action'] = $action;
		$param['created_time'] = date('Y-m-d H:i:s');
		$visitContent = serialize($param);
		$redis->lpush(date('Ymd').':visit',$visitContent);
	}
	
	public function getVisitLog(){
		$redis = $this->conn();
		$visits = $redis->Lrange(date('Ymd').':visit',0,15);
		$len = $redis->lLen(date('Ymd').':visit');
// 		echo $len.'<br>';
		$data= [];
		foreach ($visits as $k=>$val){
			$data[] = unserialize($val);
		}
		print_r($data);
	}

	public function recordSqlVisitLog($mobileType,$action,$user_id=0, $resource='', $resource_id=''){
		$visitLog = new VisitLog;
		$visitLog->page    = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$visitLog->ip      =  CU::getIp();
		$visitLog->mobile  = $mobileType;
		$visitLog->user_id = $user_id;
		$visitLog->action  = $action;
		$visitLog->resource_id = empty($resource_id)?0:$resource_id;
		$visitLog->resource    = empty($resource)?'none':$resource;
		$visitLog->save();
        

	}
}
