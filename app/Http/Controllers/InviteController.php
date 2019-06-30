<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Invite;
use App\Models\InviteUser;
use App\Models\User;
use App\Models\Task;
use App\Common\CommonUtils;
use App\Models\UserSkill;
use App\Models\SysUserSkill;
use DB, Auth, Input;
/**
 * 邀请
 * @author admin
 *
 */
class InviteController extends Controller{


	/**
	 * 创建范围邀请
	 * @param  $id 活动或任务id
	 * @param  $resourceType 类型
	 * @param  $inviteUserId 发送邀请者id
	 */
	public function createRange($id, $resourceType, $inviteUserId=0){
		if(Auth::check()){
        if($inviteUserId == 0) $inviteUserId = Auth::id();
			$skills = $this->getSysUserSkill();
			//获取技能等级
			$skillsLevels = $this->getSysUserLevel('20013');
			//获取信誉等级
			$creditLevels = $this->getSysUserLevel('20014');
			
            return view('inviterange',array(
                'skills' => $skills,
                'title'=>'范围邀请',
                'postText'=>'发送',
                'right'=>'home',
                'skillsLevels'=>$skillsLevels,
                'creditLevels'=>$creditLevels,
                'resourceId'=>$id,
                'resourceType'=>$resourceType,
                'inviteUserId'=>$inviteUserId));
		}
	}
	
	/**
	 * 创建指定邀请
	 * @param  $id 活动或任务id
	 * @param  $resourceType 类型
	 * @param  $inviteUserId 发送邀请者id
	 */
	public function  createDesignated($id, $resourceType, $inviteUserId=0){
		if(Auth::check()){
        if($inviteUserId == 0) $inviteUserId = Auth::id();
			//技能对照表
			$sysSkillsArray = [];
			$sysSkills = SysUserSkill::all();
			foreach ($sysSkills as $s){
				$sysSkillsArray[$s->code] = $s->name;
			}
			
			// $this->searchInviteUser();
            return view('invitedesignated', array(
                'title'=>'指定邀请',
                'right'=>'home',
                'postText'=>'发送',
                'sysSkillsArray'=>json_encode($sysSkillsArray),
                'resourceId'=>$id,
                'resourceType'=>$resourceType,
                'inviteUserId'=>$inviteUserId));
		}
	}

	/**
	 * 检索邀请用户
	 */
	public function searchInviteUser(){

		$sysSkillsArray = json_decode(Input::get('sysSkillsArray'), true);
		$name = Input::get('name');

		$result = [];
		//获取用户资料
		$users = User::where('display_name','like', '%'.$name.'%')->take(10)->get();
		foreach ($users as $user){
			$userArray =[];
			$userArray['id']   = $user->id;
			$userArray['name'] = $user->display_name;
			$userArray['homeAvatar'] = $user->avatar->getPath(2);
			
			//获取用户的所有技能code
			$skills = UserSkill::where('user_id', $userArray['id'])->get();
			$skill = [];
			foreach ($skills as $s){
				array_push($skill, $sysSkillsArray[$s->code]);
			}

			$userArray['skill'] = $skill;
			array_push($result, $userArray);
		}

		// echo json_encode($result);
		return response()->json($result);

	}

	//获取技能
	public function getSysUserSkill(){
		$skills = DB::table('sys_user_skill')->select('code','name')->get();

		$skillsArray = array();
		foreach ($skills as $skill) {
			$skillsArray[$skill->code] = $skill->name;
		}
		return $skillsArray;
	}
	//获取等级
	public function getSysUserLevel($code){
		$levels = DB::table('sys_attr_enum')->select('code','name')
			->where('column', $code)->get();
		$levelsArray = array();
		foreach ($levels as $level) {
			# code...
			$levelsArray[$level->code] = $level->name;
		}
		return $levelsArray;
	}


	//发送指定邀请
	public function publishDesignated(){
		$param = array();
		//判断是不是创建者
		if (Input::get('inviteUserId') == Auth::user()->id) {
			
			$from = Auth::user()->id;

			$resource_id = Input::get('resourceId');
			$resource_type = Input::get('resourceType');

			//指定邀请标记
			$type = '0';
			$idSelect = Input::get('idSelect');
			$idSelect = substr($idSelect, 0, strlen($idSelect)-1);

			$to = explode(",", $idSelect);
			
			//创建邀请
			$invite_id = $this->createInvite($to, $from, $resource_id, $resource_type, $type , count($to), $rule='');
			
			foreach ($to as $key => $user){
			
				$this->createPrivateLetter($resource_id, $resource_type, $user, $from, $invite_id, $type,'');
			}

			return response()->make(CommonUtils::ajaxReturn(1, '邀请失败', $param));
		} else {
			return response()->make(CommonUtils::ajaxReturn(-1, $msg, $param));
		}
	}

	/**
	 * 发送范围邀请
	 */
	public function publishRange(){
		$param = array();
		//判断是不是创建者
		if (Input::get('inviteUserId') == Auth::user()->id) {
			
			$user_count = Input::get('inviteNum');
			$from = Auth::user()->id;

			$resource_id = Input::get('resourceId');
			$resource_type = Input::get('resourceType');
			//范围邀请标记
			$type = '0';
		    $rule['city'] = Input::get('citySelect');
			$rule['skill'] = Input::get('inviteSkillName');
			$rule['skill_level'] = Input::get('inviteSkillLevel');
			$rule['credit_level'] = Input::get('inviteCreditLevel');
			//创建邀请
			$invite_id = $this->createInvite($to = array(), $from, $resource_id, $resource_type, $type , $user_count, $rule);
			
			//获取符合邀请条件的用户id
			$users = $this->getInviteUsers($user_count, $rule['city'], $rule['skill'], $rule['skill_level'], $rule['credit_level']);
			foreach ($users as $key => $user){
				
				$this->createPrivateLetter($resource_id, $resource_type, $user, $from, $invite_id, $type,'');
			}
			
			
			return response()->make(CommonUtils::ajaxReturn(100, '邀请失败', $param));
		} else {
			return response()->make(CommonUtils::ajaxReturn(-1, '您不是创建者', $param));
		}

			
	}
	
	
	/**
	 * 发送邀请
	 * @param  $resource_id   邀请源id
	 * @param  $resource_type 邀请源type
	 * @param  $to            被邀请者
	 * @param  $from          邀请者
	 * @param  $invite_id     邀请id
	 * @param  $type          邀请源方式（邀请还是范围邀请）
	 * @param  $statue        是否接受邀请
	 */
	public function createPrivateLetter($resource_id, $resource_type, $to, $from, $invite_id, $type, $statue = 0){


		$title        = '';
		$link         = '';
		$msg          = '';
		$variable     = array();
		//任务邀请链接
		if($resource_type == 'task'){
			// $title = Task::find($resource_id)->title;
			$link = '/task/'.$resource_id;
			$msg = 'task';
		}
		//邀请者名称
		$variable['display_name'] = Auth::user()->display_name;
		$variable['link'] = $link;
		//创建被邀请人记录
		InviteUser::create([
				'user_id'=> $to, 'resource_id'=>$invite_id, 'type'=>$type, 'statue'=>$statue
		]);
		//发送私信
		CommonUtils::createPrivateLetter($to, 0, $msg, $variable, "invite");
	}
	
	
	
	/**
	 * 创建邀请
	 * @param $to             被邀请用户id列表
	 * @param $from           发送邀请者的用户id
	 * @param $resource_id    发送邀请源id（任务或活动id）
	 * @param $resource_type  邀请源类型（任务、活动）
	 * @param $type           邀请方式（随机、指定）
	 * @param $user_count     被邀请用户人数
	 * @param $rule           邀请规则
	 */
	public function createInvite($to = array(), $from, $resource_id,
			$resource_type, $type, $user_count, $rule = array()){
		$data                  = [];
		$data['user_id']       = $from;
		$data['resource_id']   = $resource_id;
		$data['resource_type'] = $resource_type;
		$data['type']          = $type;
		$data['user_count']    = $user_count;
		if(!empty($to)){
			$data['user_id_arr']   = json_encode($to);
		} else {
			$data['user_id_arr']   = '';
		}
		if(!empty($rule)){
			$data['rule']          = json_encode($rule);
		} else {
			$data['rule']   = '';
		}

		//保存邀请规则
		$inviteData = Invite::create([
		'user_id' => $data['user_id'], 'resource_id' => $data['resource_id'], 'resource_type' => $data['resource_type'],
		'type' => $data['type'], 'user_count' => $data['user_count'], 'user_id_arr' => $data['user_id_arr'],
		'rule' => $data['rule']
		]);
		return $inviteData->id;
	}
	
	/**
	 * 获取符合邀请资格的用户id
	 * @param $selectNum   共邀请多少人
	 * @param $city        选择城市
	 * @param $skill       技能
	 * @param $skill_level 技能等级
	 * @param $credit_level信誉等级
	 */
	public function getInviteUsers($selectNum, $city, $skill, $skill_level, $credit_level){
		$result = [];
		//获取满足条件的全部用户
		$query = DB::table('t_user')->select(DB::raw('distinct t_user.id as user_id'));
        if($city > 0){
            $query = $query->leftJoin('t_user_attr', 't_user.id', '=', 't_user_attr.user_id');
        }
        $query = $query->join('t_user_skill', 't_user_skill.user_id', '=', 't_user.id');
        if($credit_level > 1){
            $query = $query->join('t_user_credit', 't_user_credit.id', '=', 't_user.id');
        }
        if($city > 0){
            $query = $query->where('t_user_attr.attr_value', $city);
        }
        $query = $query->where('t_user_skill.code', $skill);
        if($skill_level > 0){
            $query = $query->where('t_user_skill.level', '>=', $skill_level);
        }
        if($credit_level > 1){
            $query = $query->where('t_user_credit.level', '>=', $credit_level);
        }
        $users = $query->lists('user_id');
		//全部满足条件的数量
		$num = count($users);
		//全部满足条件的数量大于选择的数量
		if($num > $selectNum){
			$users_invited = DB::table('t_invite_user')->select(DB::raw('distinct user_id'))
				->orderBy('created_at', 'asc')->lists('user_id');
			$result = array_diff($users, $users_invited);
			$invitedNum = count($result);
			//要邀请的人数没有达标
			if($invitedNum < $selectNum){
				$diffNum = $selectNum - $invitedNum;
				$result = array_merge($result, array_slice($users_invited, 0, $diffNum));
			//要邀请的人数已经超了
			} else if($invitedNum > $selectNum){
				$result = array_merge($result, array_slice($users_invited, 0, $selectNum));
			}
		} else {
			$result = $users;
		}
		return $result;
	}

}


?>
