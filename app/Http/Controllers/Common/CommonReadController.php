<?php

namespace App\Http\Controllers\Common;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\IpUserStatus;

use Auth, Input;

class CommonReadController extends Controller
{
	
	public function postRead(){
		if(Auth::check()){
			$read = Input::get('read');
			$resourceId = intval(Input::get('resourceId'));
			$id = $this->updateIpUserStatus(Auth::user()->id, $resourceId,$read);
			return "true";				
		}else{
			return "false";
		}
	
	}
	
	private function updateIpUserStatus($userID,$resourceId,$read){
		$model = IpUserStatus::firstOrNew(array('user_id'=>$userID, 'ip_id'=>$resourceId));
		$model->status = $read;
		$model->save();
		return $model->id;
	}
}
