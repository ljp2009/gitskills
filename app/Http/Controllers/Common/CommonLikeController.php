<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Ip;
use App\Models\LikeModel;
use App\Models\LikeSumModel;
use App\Common\GoldManager as GM;
use App\Common\Notifaction;
use App\Common\IpContributorHandler;

use Auth, Input, DB;

class CommonLikeController extends Controller
{

	public static function getLikeKey($resource, $resourceId){
		return $resource.'@'.$resourceId;
	}

	public function attachLikes($resourceName, $resources){
		$login = Auth::check();
		if(sizeof($resources)>0){
			foreach($resources as $resource){
				$resource->likeCount = self::getLikeCount($resourceName, $resource->id);
				if($login){
					$resource->iLike = $this->isUserLike($resourceName, $resource->id);
				}else{
					$resource->iLike = false;
				}
			}
			return $resources;
		}else{
			return $resources;
		}
	}
	public function getLikeSettings($resources, $resourceIds){
		$result = array();
		for($i=0; $i<sizeof($resources); $i++){
			if(self::getLikeCount($resources[$i], $resourceIds[$i]) > 0){
				$k = CommonLikeController::getLikeKey($resources[$i], $resourceIds[$i]);
				$r = array($k=>1);
				array_push($result, $r);
			}
		}
		return $result;
	}

	public static function getLikeCount($resource, $resourceId){
		$result = LikeModel::where('resource', $resource)->where('resource_id', $resourceId)->count();

		return $result;
	}

	public static function isLoginUserLike($resource, $resourceId){
		if(!Auth::check()){
			return -1;
		}else{
			$result = LikeModel::where('resource', $resource)
			->where('resource_id', $resourceId)
			->where('user_id', Auth::user()->id)->count();
			if($result>0){
				return 1;
			}else{
				return 0;
			}
		}
	}

	public function isUserLike($resource, $resourceId, $userId=0){
		if($userId===0){
			$userId = Auth::user()->id;
		}
        $result = LikeModel::where('resource', $resource)
            ->where('resource_id', $resourceId)
            ->where('user_id', $userId)
            ->where('like_sum_id','>', 0)
            ->count();
		return ($result>0);
	}

    public function switchLike(){
		$resource = Input::get('resource');
		$resourceId = intval(Input::get('resourceId'));
        if(!Auth::check()){
            return response()->json(['res'=>false, 'info'=> 'notlogin']);
        }
        $userId = Auth::user()->id;
        $query = LikeModel::where('resource', $resource)
            ->where('resource_id', $resourceId);
        $query = $query->where('user_id', $userId);
        $likeModel = $query->first();
        $isLike = false;
        //从未like过
        if(is_null($likeModel)){
            $isLike = true;
            $this->updateLikeSum($resource, $resourceId);
            $likeModel = new LikeModel;
            $likeModel->user_id=$userId;
            $likeModel->resource = $resource;
            $likeModel->resource_id = $resourceId;
            $likeModel->like_sum_id = 1;
            $likeModel->save();
            //第一次like的时候给作者加金币
            $this->giveOwnerGold($resource, $resourceId);
            //发送被like的消息
            Notifaction::Notice(Notifaction::LIKE, Auth::user(), $resource, $resourceId);
            
        }
        else{//已经like了
            if($likeModel->like_sum_id>0){
                $this->updateLikeSum($resource, $resourceId, -1);
                $likeModel->like_sum_id = -1;
                $likeModel->save();
            }else{//曾经like过
                $isLike = true;
                $this->updateLikeSum($resource, $resourceId);
                $likeModel->like_sum_id = 1;
                $likeModel->save();
            }
        }
        return response()->json(['res'=>true, 'isLike'=> $isLike]);
    }

	private function updateLikeSum($resource, $resourceId, $num = 1){
        $entTab = $this->getLikeEntityTable($resource);
        $entity = DB::table($entTab)->where('id', $resourceId)->first();
        $likeSum = $entity->like_sum + $num;
        DB::table($entTab)->where('id', $resourceId)->update(['like_sum' => $likeSum]);
		return $num;
	}
    private function giveOwnerGold($resource, $resourceId){
        $map = [
            'ip'                => ['t_ip'               , 'user_id', 20, '5000124', ', id'],
            'task_delivery'     => ['t_task_delivery'    , 'user_id', 0 , '5000124', ', id'],
            'ip_scene'          => ['t_ip_scene'         , 'user_id', 20, '5000107', ', ip_id'],
            'ip_dialogue'       => ['t_ip_dialogue'      , 'user_id', 20, '5000107', ', ip_id'],
            'ip_role'           => ['t_ip_role'          , 'user_id', 20, '5000107', ', ip_id'],
            'user_production'   => ['t_user_production'  , 'user_id', 20, '5000105', ', ip_id'],
            'dimension_publish' => ['t_dimension_publish', 'user_id', 20, '5000104', ''],
            'discussion'        => ['t_discussion'       , 'user_id', 5 , '5000124', '']
        ];
        if(!array_key_exists($resource, $map)) return;

        $set = $map[$resource];
        
        $obj = DB::table($set[0])
            ->where('id', $resourceId)
            ->select(DB::raw($set[1] . $set[4]))
            ->first();
         
        $userId = $obj->$set[1];
        $ipId = 0;
        switch($resource){
        case 'ip':
            $ipId = $obj->id;
            break;
        case 'ip_scene':
        case 'ip_dialogue':
        case 'ip_role':
        case 'user_production':
            $ipId = $obj->ip_id;
            break;
        default:
            $ipId = 0;
        }
        if($ipId > 0){
            IpContributorHandler::updateUserReceiveGold($ipId, $resource, $resourceId, $set[2]);
        }
        $payResult = GM::incomeGold($set[2], $set[3], $resourceId, $userId, '被like奖励'.$set[2].'金币', true);
    }

    private function getLikeEntityTable($resource){
        $tab = [
            'ip'                => 't_ip',
            'ip_scene'          => 't_ip_scene',
            'ip_dialogue'       => 't_ip_dialogue',
            'ip_role'           => 't_ip_role',
            'user_production'   => 't_user_production',
            'dimension_publish' => 't_dimension_publish',
            'discussion'        => 't_discussion'
        ];
        if(array_key_exists($resource, $tab)){
            return $tab[$resource];
        }
        return 't_'.$resource;
    }

	public function postLikeAndReturnCount(){
		$resource = Input::get('resource');
		$resourceId = intval(Input::get('resourceId'));

		if(Auth::check()){
			$userId = Auth::user()->id;
			if(!$this->isUserLike($resource, $resourceId, $userId)){
				$sumid = $this->updateLikeSum($resource, $resourceId);
				$obj = new LikeModel;
				$obj->user_id=$userId;
				$obj->resource = Input::get('resource');
				$obj->resource_id = Input::get('resourceId');
				$obj->like_sum_id = $sumid;
				$obj->save();
			}
			return self::getLikeCount($resource, $resourceId);
		}else{
			return self::getLikeCount($resource, $resourceId);
		}
	}
	public function postLike(){
		if(Auth::check()){
			$resource = Input::get('resource');
			$resourceId = intval(Input::get('resourceId'));
			if(!$this->isUserLike($resource, $resourceId, Auth::user()->id)){
                $sumid = $this->updateLikeSum($resource, $resourceId);
                $obj = new LikeModel;
                $obj->user_id=Auth::user()->id;
                $obj->resource = Input::get('resource');
                $obj->resource_id = Input::get('resourceId');
                $obj->like_sum_id = $sumid;
                $obj->save();
            }
			return "true";
		}else{
			return "false";
		}
	}

    /*
     * 通用按照like排序的方法(使用sum表排序，效率更快)
     * $resource 需要排序的对象名称
     * $fieldConditions array 查询限定条件，数组中包含$fieldName=>$fieldValue，可以包含多组
     * $from, $to 数据位置
     * $orderBy 排序规则
     * */
    public static function getLikeOrderList($resource, $fieldConditions, $from, $to, $orderBy='desc'){
        $resourceTable = self::getResourceTable($resource);
        $query = DB::table('t_like_sum')->select($resourceTable.'.id','t_like_sum.like_sum')
            ->join($resourceTable,$resourceTable.'.id','=','t_like_sum.resource_id')
            ->where('t_like_sum.resource',$resource);
        if(is_array($fieldConditions)){
            foreach($fieldConditions as $fieldName=>$fieldValue){
                if(substr($fieldName,0,1)=='!'){
                    
                    $query = $query->where($resourceTable.'.'.substr($fieldName,1), '!=', $fieldValue);
                    continue;
                }
                if(is_array($fieldValue)){
                    $query = $query->whereIn($resourceTable.'.'.$fieldName, $fieldValue);
                    continue;
                }
                $query = $query->where($resourceTable.'.'.$fieldName, $fieldValue);
            }
        }
        $ids =$query->orderBy('t_like_sum.like_sum', $orderBy)->skip($from)->take($to-$from+1)->get();
        $idArr = [];
        for($i=0;$i<count($ids);$i++){
            $idArr[$i] =['id'=>$ids[$i]->id, 'sum'=>$ids[$i]->like_sum];
        }
        return $idArr;
    }
    private static function getResourceTable($resourceName){
        $resourceTable = '';
        switch($resourceName){
        case 'ip_colleague':
            $resourceTable = 't_user_production';
            break;
        case 'ip_peripheral':
            $resourceTable = 't_user_production';
            break;
        case 'ip_discussion':
            $resourceTable = 't_user_production';
            break;
        default:
            $resourceTable = 't_'.$resourceName;
        }
        return $resourceTable;
    }
    /*
     * 获取无人like的作品(用于填充到like列表的结尾，调用的时候需要注意from和to的值)
     * $resource 需要排序的对象名称
     * $fieldConditions array 查询限定条件，数组中包含$fieldName=>$fieldValue，可以包含多组
     * $from, $to 数据位置
     * $orderBy 排序规则
     * */
    public static function getNoLikeOrderList($resource, $fieldConditions, $from, $to, $orderBy='desc'){
        $resourceTable = 't_'.$resource;
        $query = DB::table($resourceTable)->select($resourceTable.'.id','like_sum')
            ->leftJoin('t_like_sum', function($join){
                $join->on($resourceTable.'.id', '=', 't_like_sum.resource_id')
                    ->where('t_like_sum.resource', $resource);
            });
        if(is_array($fieldConditions)){
            foreach($fieldConditions as $fieldName=>$fieldValue){
                $query = $query->where($resourceTable.'.'.$fieldName, $fieldValue);
            }
        }
        $ids =$query ->orderBy($resourceTable.'.created_at', $orderBy)->skip($from)->take($to-$from+1)->get();
        $idArr = [];
        for($i=0;$i<count($ids);$i++){
            $idArr[$i] =['id'=>$ids[$i]->id, 'sum'=>0];
        }
        return $idArr;
    }
     /*
     * 获取like的计数
     * $resource 对象名称
     * $idArr id列表
     * */
    public static function getResourceLikeSum($resource, $idArr){
        $sums = LikeSumModel::where('resource', $resource)->whereIn('resource_id', $idArr)->get();
        $resArr = [];
        foreach($sums as $sum){
            $resArr[$sum->resource_id] = $sum->like_sum;
        }
        return $resArr;
    }
    // 通用按照like排序的方法(实时演算排序，效率低，验证用)
    public static function getLikeOrderList_actual($resource, $fieldName, $fieldValue, $from, $to, $orderBy){
        $resourceTable = 't_'.$resource;
        $ids = DB::table('t_like')->select($resourceTable.'.id')->join($resourceTable,$resourceTable.'.id','=','t_like.resource_id')
            ->where($resourceTable.'.'.$fieldName, $fieldValue)
            ->where('t_like.resource',$resource)
            ->groupBy('t_like.resource_id')
            ->orderBy(DB::raw('count(1)'))->skip($from)->take($to-$from+1)->get();
        return $ids;
    }
}
