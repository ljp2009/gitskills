<?php
/**
 * @date   2016-1-26
 * @author ViewsTap Programmer xiaocui
 * 记录用户的偏好
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonHandelGoldController;
use App\Models\User;
use App\Models\UserDetailStatus;
use App\Models\UserAttr;
use App\Common\CommonUtils;
use App\Models\SysUserSkill;
use App\Models\UserSurvey;
use App\Models\UserPreference;
use App\Models\SysIpSurvey;
use Auth, Redirect, Input,DB;

class UserPreferenceController extends Controller
{
	public static $USER_PREFRENCE = array(
		'5000101' => '萌',
		'5000102' => '宅',
		'5000103' => '傲',
		'5000104' => '贱',
		'5000105' => '腐',
		'5000106' => '燃',
	);
	public static $USER_PREFRENCE_SCORE = array(
			'5000101' => 10,
			'5000102' => 20,
			'5000103' => 20,
			'5000104' => 70,
			'5000105' => 60,
			'5000106' => 55,
	);
	protected static $USER_SCORE = array(
			'5000101' => 0,
			'5000102' => 0,
			'5000103' => 0,
			'5000104' => 0,
			'5000105' => 0,
			'5000106' => 0,
	);
	
	public function showUserPrefrence()
	{
		$ipSurvey = SysIpSurvey::whereIn('type',array('cartoon','story','game'))->get();
// 		print_r($ipSurvey);
		$cartoon = $story = $game = [];
		foreach($ipSurvey as $k=>$val){
			if($val['type'] == 'cartoon'){
				$cartoon[] = $val;
			}else if($val['type'] == 'story'){
				$story[] = $val;
			}else if($val['type'] == 'game'){
				$game[] = $val;
			}
		}
		return view('partview.user.ipsurvey', array('cartoon'=>$cartoon,'story'=>$story,'game'=>$game));
	}
	
	public function addUserPrefrence(){
		$uid = Input::get('uid');
		$prefrence = Input::get('prefrence');
// 		print_r($prefrence);
		foreach($prefrence as $v){
			UserSurvey::create(['user_id'=>$uid,'survey_id'=>$v]);
		}
		$this->calculateUserPrefrence($uid);
		return redirect('/auth/createskill/' . $uid);
	}
	
	public function calculateUserPrefrence($uid){
		$surveyIDs = DB::table('t_user_survey')->where('user_id',$uid)->lists('survey_id');
		$ipSurvey = SysIpSurvey::whereIn('id',$surveyIDs)->get()->toArray();
		$current = self::$USER_SCORE;
		foreach($ipSurvey as $key=>$val){
			$ipAttrs = json_decode($val['attrs'],true);
			foreach($ipAttrs as $k=>$v){
				if(array_key_exists($k,$current)){
					$current[$k] = $current[$k] + $v;
				}
			}
		}
// 		print_r($current);die;
		$condition = self::$USER_PREFRENCE_SCORE;
		$userPrefrence = self::$USER_PREFRENCE;
		foreach($current as $code=>$value){
			if($value >= $condition[$code]){
				UserPreference::create(['user_id'=>$uid,'code'=>$code,'value'=>$userPrefrence[$code]]);
			}
		}
	}
}
?>