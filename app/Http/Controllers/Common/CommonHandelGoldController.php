<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Common\CommonUtils;
use App\Models\UserDetailStatus;
use App\Models\UserGoldRecord;
use Auth, Input;

class CommonHandelGoldController extends Controller
{
	private $TYPE = array('income','pay');
	private $SYSTEMGOLD = array('PKTASK');
	/**
	 * 记录用户金币的变动
	 * @param number $gold 金币
	 * @param unknown $resource 资源类型
	 * @param unknown $resourceID 对应id
	 * @param number $userID 相关联的用户id,如果没有涉及，可不传
	 * @param string $type 类型：income:收入   pay：支出
	 */
	public function commonGoldRecord($gold,$resource,$resource_id,$userID = 0,$type = 'income'){
		if(!in_array($type,$this->TYPE)){
			return ;
		}
		$data = $data1 = array();
		if(Auth::check()){
			$userDetailStatus = UserDetailStatus::where('user_id',Auth::user()->id)->first();
			if($userID != 0){
				$userRelationDetailStatus = UserDetailStatus::where('user_id',$userID)->first();
			}
			switch ($type){
				case 'income':
					$userDetailStatus->receive_gold = $userDetailStatus->receive_gold + $gold;
					$userDetailStatus->save();
					
					break;
				case 'pay':
					if($gold > $userDetailStatus->gold){
						$return = CommonUtils::ajaxReturn(-1,'您的金币不足！');
						return $return;
					}
					$userDetailStatus->gold = $userDetailStatus->gold - $gold;
					$userDetailStatus->save();
					if($userID != 0){
						$userRelationDetailStatus->receive_gold = $userRelationDetailStatus->receive_gold + $gold;
						$userRelationDetailStatus->save();
					}
					break;
			}
			if($userID == 0){
				$data['user_id'] = Auth::user()->id;
				$data['resource'] = $resource;
				$data['resource_id'] = $resource_id;
				$data['type'] = $type;
				$data['gold'] = $gold;
				$new = $this -> createUserGoldRecord($data);
				
			}else if($userID && $type == 'income'){
				$data['user_id'] = Auth::user()->id;
				$data['resource'] = $data1['resource'] = $resource;
				$data['resource_id'] = $data1['resource_id'] = $resource_id;
				$data['type'] = $type;
				$data['gold'] = $data1['gold'] = $gold;
				$new = $this -> createUserGoldRecord($data);
				$data1['type'] = 'pay';
				$data1['user_id'] = '$userID';
				$this -> createUserGoldRecord($data1);
			}else if($userID && $type == 'pay'){
				$data['user_id'] = Auth::user()->id;
				$data['resource'] = $data1['resource'] = $resource;
				$data['resource_id'] = $data1['resource_id'] = $resource_id;
				$data['type'] = $type;
				$data['gold'] = $data1['gold'] = $gold;
				$new = $this -> createUserGoldRecord($data);
				$data1['type'] = 'income';
				$data1['user_id'] = $userID;
				$this -> createUserGoldRecord($data1);
			}
			if($new -> id > 0){
				$return = CommonUtils::ajaxReturn(1,'成功');
				return $return;
			}
		}elseif(in_array($resource,$this->SYSTEMGOLD)){
			$userDetailStatus = UserDetailStatus::where('user_id',$userID)->first();
			switch ($type){
				case 'income':
					$userDetailStatus->receive_gold = $userDetailStatus->receive_gold + $gold;
					$userDetailStatus->save();
						
					break;
				case 'pay':
					$userDetailStatus->gold = $userDetailStatus->gold - $gold;
					$userDetailStatus->save();
					break;
			}
			if($userID && $type == 'income'){
				$data['user_id'] = $userID;
				$data['resource'] = $resource;
				$data['resource_id'] = $resource_id;
				$data['type'] = $type;
				$data['gold'] = $gold;
				$this -> createUserGoldRecord($data);
			}else if($userID && $type == 'pay'){
				$data['user_id'] = $userID;
				$data['resource'] = $resource;
				$data['resource_id'] = $resource_id;
				$data['type'] = $type;
				$data['gold'] = $gold;
				$this -> createUserGoldRecord($data);
			}
		}
// 		return;
		
	}
	/**
	 * 点击金币收钱
	 */
	public static function commonReceiveMoney(){
		if(Auth::check()){
			$userDetailStatus = UserDetailStatus::where('user_id',Auth::user()->id)->first();
			if($userDetailStatus->receive_gold > 0){
				$userDetailStatus->gold = $userDetailStatus->gold + $userDetailStatus->receive_gold;
				$userDetailStatus->receive_gold = 0;
				$i = $userDetailStatus->save();
				if($i){
					$return = CommonUtils::ajaxReturn(1,'成功');
					return $return;
				}
				return $i;
			}
		}
	}
	
	private function createUserGoldRecord($data){
		return UserGoldRecord::create([
			'user_id' => $data['user_id'],
			'resource' => $data['resource'],
			'resource_id' => $data['resource_id'],
			'type' => $data['type'],
			'gold' => $data['gold'],
		]);
	}
}
