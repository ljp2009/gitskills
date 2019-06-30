<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use App\Common\CommonUtils;
use App\Models\UserSkill;
use App\Models\SysUserSkill;
use App\Models\UserProduction;
use App\Models\CertificationApply;
use DB, Auth, Input;

/**
 * 认证申请
 * @author admin
 *
 */
class CertificationApplyController extends Controller{


	//申请列表页面
	public function showCertificationList($listName){
		
		return view('certification.certificationlist', array('page'=>'0','listName'=>$listName));
	}

	//展示全部申请记录
	public function loadCertificationList($listName, $from, $to){
		if (Auth::check()) {
			# code...
			$id = Auth::user()->id;
			$certificationlist = [];
			//全部
			if ($listName == 0) {
				# code...
				$list = CertificationApply::where('user_id', $id)
							->where('delete', '<>',  1)
							->orderBy('created_at', 'desc')->get();
			//未完成
			} else if ($listName == 1) {
				# code...
				$list = CertificationApply::where('user_id', $id)
							->where('status', '<>', 2)
							->where('delete', '<>', 1)
							->orderBy('created_at', 'desc')->get();
			//已完成
			} else if($listName == 2){
				$list = CertificationApply::where('user_id', $id)
							->where('status', '=',  2)
							->where('delete', '<>', 1)
							->orderBy('created_at', 'desc')->get();
			}

			foreach ($list as $value) {
				# code...
				array_push($certificationlist, [
						'id'             => $value->id,
						'skillName'      => $value->skillName,
						'skillLevelName' => $value->skillLevelName,
						'certifiResult'  => $value->certifiResult,
						'status'         => $value->status,
						'result'         => $value->result,
						'createdDate'    => $value->createdDate
					]);
			}
			return view('certification.partview.certificationlistitem',
							array('certificationlist'=>$certificationlist));
		}
		
	}
	//删除认证申请
	public function deleteCertifiApply(){
		if (Auth::check()) {
			//认证信息的id
			$id     = Input::get('id');
			//认证状态
			$status = Input::get('status');
			$arr=[];
			//待审核
			if ($status == 0) {
				# code...
				// CertificationApply::where('id',$id)->where('user_id', Auth::user()->id)->delete();
			//审核结束
			} else if ($status == 2) {

				CertificationApply::where('id', $id)->where('user_id', Auth::user()->id)->update(['delete'=>1]);
			}

		}
		
		return response()->json($arr);
	}

	/**
	 * 引导页
	 */
	public function createGuidePager(){
		return view('certification.showguidepager',array());
	}
	
	//编辑认证申请
	public function editCertification($id){
		if (Auth::check()) {

			//获取要编辑的认证申请
			$editCertifi = CertificationApply::where('id', $id)->first();
			//已选技能
			$mySkill = $editCertifi->skill;
			//已选技能等级
			$mySkillLevel = $editCertifi->skill_level;

			return $this->baseCertification($mySkill,$mySkillLevel);
		}
	}

	/**
	 * 发起认证申请
	 */
	public function createCertification(){
		if(Auth::check()){
			
			return $this->baseCertification('','');
		}
	}
	
	public function baseCertification($mySkill, $mySkillLevel){
			$addProMaxNum = '3';
			$skills = $this->getSysUserSkill();
			//获取技能等级
			$skillsLevels = $this->getSysUserLevel('20013');
			$skills = $this->getSysUserSkill();
			//获取技能等级
			$skillsLevels = $this->getSysUserLevel('20013');
				
			return view('certification.createcertification',array('skills' => $skills,
					'skillsLevels'=>$skillsLevels, 'addProMaxNum'=>$addProMaxNum,
					'mySkill'=>$mySkill, 'mySkillLevel'=>$mySkillLevel));
	}


	/**
	 * 获取用户技能
	 * @return
	 */
	private function  getSysUserSkill(){
		$skills = DB::table('sys_user_skill')->select('code', 'name')->get();
		$skillsArray = array();
		foreach ($skills as $skill){
			$skillsArray[$skill->code] = $skill->name;
		}
		return $skillsArray;
	} 
	/**
	 * 获取等级
	 * @param $code
	 * @return
	 */
	private function getSysUserLevel($code){
		$levels = DB::table('sys_attr_enum')->select('code', 'name')
			->where('column', $code)->get();
		$levelsArray = array();
		foreach ($levels as $level){
			$levelsArray[$level->code] = $level->name;
		}
		return $levelsArray;
	}

	//快速查询自己的作品
	public function postQuickSearch(){
		//作品名称
		$keywd = Input::get('keywd');
		//用户id
		$user_id = Auth::user()->id;
		//原创
		$is_original = '1';
		$arr = [];
		if(trim($keywd) != ''){
			$pros = UserProduction::where('name', 'like', '%'. $keywd .'%')
				->where('user_id', $user_id)
				->where('is_original', $is_original)
				->take(10)->get();
			foreach ($pros as $pro) {
				array_push($arr, [
					'id'   =>$pro->id,
					'cover'=>$pro->cover,
					'name' =>$pro->name,
					'intro'=>$pro->intro
				]);
			}
		}
		return response()->json($arr);
	}
	//认证申请
	public function apply(){
		$param = [];
		//用户id
		$user_id = Auth::user()->id;
		//技能名称
		$appSkillName = Input::get('skillName');
		//技能等级
		$appSkillLevel = Input::get('skillLevel');
		//说明
		$instr = Input::get('instruction');
		//选中的作品
		$proIdArr = Input::get('pro_id');
		//状态
		$status = 0;

		//没有选择作品
		if(count($proIdArr) < 1){

			return response()->make(CommonUtils::ajaxReturn(-1, '请选择作品', $param));
		}
		if($this->isSkillApplyed($user_id, $appSkillName)){
			return response()->make(CommonUtils::ajaxReturn(-1, '该技能认证已经在审核中，不能重复申请', $param));
		}
		
		$count = DB::table('t_user_attr')
			->where('user_id',   $user_id)
			->where('attr_code', $appSkillName)
			->where('attr_value','>=',$appSkillLevel)
			->count();
		//认证的技能级别需要不大于当前用户已有级别		
		if ($count > 0) {
			return response()->make(CommonUtils::ajaxReturn(-1, '申请技能的等级比当前用户的等级低', $param));
		}

		CertificationApply::create(['user_id'=>$user_id, 'skill'=>$appSkillName,
			'skill_level'=>$appSkillLevel, 'instruction'=>$instr,'ip_reference'=>json_encode($proIdArr),
			'status'=>$status]);
		return response()->make(CommonUtils::ajaxReturn(100, '申请认证技能发起失败', $param));
	}

	//判断该用户技能是否已经在审核中
	private function isSkillApplyed ($user_id, $appSkillName){
		$count = DB::table('t_certification_apply')
			->where('user_id',   $user_id)
			->where('skill', $appSkillName)
			->where('status', '<>', '2')
			->count();
		//正在审核中
		if($count > 0){
			return true;
		}
		return false;
	}
}


?>