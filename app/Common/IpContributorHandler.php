<?php
/*
 * 
* */
namespace App\Common;


use Config;

use App\Models\IpContributor;
use App\Models\IpSum;
class IpContributorHandler{
	
	/**
	 * 涉及创建IP、发布场景、发布台词、发布角色、发布长评、发布同人、发布周边画面
	 * @param $ip_id
	 * @param $user_id
	 * @param $resource_id
	 * @param $resource
	 */
	public static function SaveIpContributor($ip_id, $user_id, $resource_id, $resource){
		$ipContributor = new IpContributor;
		$ipContributor->ip_id       = $ip_id;
		$ipContributor->user_id     = $user_id;
		$ipContributor->resource_id = $resource_id;
		$ipContributor->resource    = $resource;
		$ipContributor->save();

		$count = IpContributor::where('ip_id', '=', $ip_id)->where('user_id', '=', $user_id)->count();

		//刚刚参与
		if ($count == 1) {
			$ipSum = IpSum::where('ip_id', '=', $ip_id)->first();
			if (!empty($ipSum)) {
				$ipSum->value = $ipSum->value+1;
			} else {
				$ipSum        = new IpSum;
				$ipSum->ip_id = $ip_id;
				$ipSum->value = 1;
				$ipSum->code  = '11001';

			}
			$ipSum->save();
		}

	}
    public static function updateUserReceiveGold($ipId, $resource, $resourceId, $gold){
        $query = IpContributor::where('ip_id', $ipId)
                ->where('resource_id', $resourceId);
        if($resource == 'user_production'){
            $query = $query->whereIn('resource', ['coll', 'peri', 'disc']);
        }else{
            $query = $query->where('resource', $resource);
        }
        $con = $query->first();
        if(is_null($con)) return ;
        $con->receive_gold += $gold;
        $con->save();

    }
    
}

?>
