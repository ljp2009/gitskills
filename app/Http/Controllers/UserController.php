<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonHandelGoldController;
use App\Models\User;
use App\Models\LikeModel;
use App\Models\UserRelation;
use App\Models\UserDetailStatus;
use App\Models\UserPrivateLetter;
use App\Models\UserAttr;
use App\Models\UserSum;
use App\Models\IpContributor;
use App\Common\CommonUtils;
use App\Models\UserProduction;
use App\Models\UserGoldRecord;
use App\Models\SysAttrEnum;
use App\Models\SysUserSkill;
use App\Models\UserSkill;
use App\Common\GoldManager as GM;
use App\Common\Image;
use Auth, Redirect, Input,DB;

class UserController extends Controller
{

    public function showUserList($listName,$page, $pid='')
	{
        $titleMap = [ 'follow'=>'关注列表', 'fans'=>'粉丝列表' ];
        $params = ['pid'=>$pid,'page'=>$page,'listName'=>$listName];
        if(array_key_exists($listName, $titleMap)){
            $params['title'] = $titleMap[$listName];
        }
		return view('userlist', $params);
	}

	public function loadListData($from, $to, $pid='')
	{
// 		$userRelation = UserRelation::hasMany('UserRelation','follow_id')->where('follow_id','=', $pid)->take(4)->get();
		$model = UserRelation::where('follow_id', $pid);
		$userRelation = CommonUtils::handleListDetails($model, $from, $to);
		$list = array();
		foreach ($userRelation as $key => $value) {
			array_push($list, $value->getUserInfo);
		}
		return view('partview.useritem', array('models'=>$list));
	}
	public function loadFollowListData($from, $to, $pid='')
	{
// 		$userRelation = UserRelation::hasMany('UserRelation','follow_id')->where('follow_id','=', $pid)->take(4)->get();
		$model = UserRelation::where('user_id', $pid);
		$userRelation = CommonUtils::handleListDetails($model, $from, $to);
		$list = array();
		foreach ($userRelation as $key => $value) {
			array_push($list, $value->getFollowInfo);
		}
		return view('partview.useritem', array('models'=>$list));
	}

	public function loadFansListData($from, $to, $pid)
	{
		$model = UserRelation::where('fans_id', $pid);
		$userRelation = CommonUtils::handleListDetails($model, $from, $to);
// 		print_r($userRelation);
		$list = array();
		foreach ($userRelation as $key => $value) {
			array_push($list, $value->getUserInfo);
		}
		return view('partview.useritem', array('models'=>$list));
	}

	public function loadsameListData($from, $to, $pid)
	{
		if(Auth::check()){
			$users = Auth::user();
		}
		$model = LikeModel::where('resource_id', $pid);
		$userLike = CommonUtils::handleListDetails($model, $from, $to);
		$list = array();
		foreach ($userLike as $key => $value) {
			array_push($list, $value->getUserInfo);
		}
		return view('partview.useritem', array('models'=>$list));
	}

	public function getletterList($page, $pid)
	{
		return view('privatelist', array('pid'=>$pid,'page'=>$page,'name'=>'我的私信列表'));
	}
	//我的私信列表
	public function getletterListData($from, $to, $pid)
	{
		if(Auth::check()){
			$users = Auth::user();
		}
		$user_id = $users->id;
		$to = $to - $from +1;
		
		$privateList = DB::select(DB::raw("select max(id) as id, case when user_id=$user_id then send_id else user_id end as from_id from t_user_private_letter where user_id=$user_id or send_id =$user_id 
							group by( case when user_id=$user_id then send_id else user_id end) order by max(id) desc limit $from, $to"));


		foreach ($privateList as $k => $v){
			
            if($v->from_id == 0){
                $from_user = new User;
                $from_user->display_name = '系统';
                $from_user->id = 0;

            }else{
                $from_user = User::findOrFail($v->from_id);
            }
			$letter = UserPrivateLetter::findOrFail($v->id);

			$variableArr = !empty($letter['variable']) ? json_decode($letter['variable'], true) : '';
			$privateList[$k]->link   = !empty($variableArr) ? $variableArr['link'] : '';
			$privateList[$k]->msg    = CommonUtils::readPrivateLetter($letter['msg'],$letter['type'],$letter['variable']);
			$privateList[$k]->time   = CommonUtils::dateFormatting(strtotime($letter['created_at']));
			$privateList[$k]->status = $letter['status'];
			$privateList[$k]->avatar =Image::makeImage($from_user['avatar']);
			$privateList[$k]->display_name = $from_user['display_name'];
			$privateList[$k]->to_user_id = $from_user['id'];

		}

		return view('partview.privateitem', array('models'=>$privateList));
	}
	//与用户的私信对话
	public function getletterListDialog($page, $to_user_id = 0){
            if($to_user_id == 0){
                $from_user = new User;
                $from_user->display_name = '系统';
                $from_user->id = 0;
                $title = '系统消息';

            }else{
                $from_user = User::findOrFail($to_user_id);
                $title = '与'.$from_user['display_name'].'的私信列表';
            }

		return view('privatelistdialog', array('userId'=>$to_user_id, 'to_user_id'=>$to_user_id, 'page'=>$page,'title'=>$title));
	}

	public function getletterListDialogData($from, $to, $to_user_id=0){
		if(Auth::check()){
			$users = Auth::user();
		}
		$user_id = $users->id;
		$to = $to - $from +1;
		$privateList = DB::select(DB::raw("select * from t_user_private_letter where  (user_id=$user_id and send_id=$to_user_id) 
			or (send_id =$user_id and user_id = $to_user_id) order by id desc limit $from, $to "));

		foreach ($privateList as $k => $value) {
            if($value->send_id == 0){
                $user = new User;
                $user->display_name = '系统';
                $user->id = 0;
            }else{
                $user = User::findOrFail($value->send_id);
            }
			$privateList[$k]->display_name = $user['display_name'];
			$privateList[$k]->avatar       = Image::makeImage($user['avatar']);
			$privateList[$k]->time         = CommonUtils::dateFormatting(strtotime($privateList[$k]->created_at));
			$privateList[$k]->isOwner      = (Auth::id() == $value->send_id);
			$privateList[$k]->formatMsg    = CommonUtils::readPrivateLetter($value['msg'],$value['type'],$value['variable']);
		}

		return view('partview.privatedialogitem', array('models'=>$privateList));
	}

	public function getletterInfo($id){
		$private = UserPrivateLetter::findOrFail($id);
		$private['msg'] = CommonUtils::readPrivateLetter($private['msg'],$private['type'],$private['variable']);
		return view('privateinfo', array('id'=>$id,'model'=>$private,'name'=>'详情'));
	}

	public  function updatePrivate(Request $request){
		$id = $request->input('id');
		$private = UserPrivateLetter::findOrFail($id);
		$private->status = 'Y';
		$private->save();
		$return = $this->ajaxReturn(1);
		echo $return;
		exit;
	}
    /*新版关注代码*/
    public function FollowSwitch(Request $request){
       $userId = Auth::user()->id;
       $targetUserId = $request->input('id');
       $relation = UserRelation::where('user_id', $userId)
           ->where('follow_id', $targetUserId)->first();
  
       $followData = [];

       if(is_null($relation)){//关注
           $userRelation = new UserRelation;
           $userRelation->follow_id = $targetUserId;
           $userRelation->fans_id = $targetUserId;
           $userRelation->user_id = $userId;
           $userRelation->save();
           $followData['code'] = '1';
           $fansSumValue = $this->updateUserSum($userId, $targetUserId, 1);

       }else{
           //取消关注
           $relation->delete();
           $followData['code'] = '2';
           $fansSumValue = $this->updateUserSum($userId, $targetUserId, -1);

       }

	   $followData['value'] = $fansSumValue;

       return $followData;
    }

    //更新关注数和粉丝数
	public function updateUserSum($userId, $targetUserId, $num){

       $userSums = UserSum::whereIn('user_id', [$userId, $targetUserId])
           ->whereIn('sum_code',['21001','21002'])->get();
       $fansSum = null;
       $followSum = null;
       foreach($userSums as $us){
       	   //对象粉丝数
           if($us->user_id == $targetUserId && $us->sum_code == '21002'){
               $fansSum = $us;
           //用户关注数
           }elseif($us->user_id == $userId && $us->sum_code == '21001'){
               $followSum = $us;
           }
       }
       
       
       if (is_null($followSum)) {
       	# code...
       		$followSum = new UserSum;
       		$followSum->user_id = $userId;
       		$followSum->sum_code = '21001';
       		$followSum->value = '1';
       		$followSum->save();
       } else {
       		$followSum->value = strval(intval($followSum->value) + $num);
       		$followSum->save();
       }

       if (is_null($fansSum)) {
       	# code...
       		$fansSum = new UserSum;
       		$fansSum->user_id = $targetUserId;
       		$fansSum->sum_code = '21002';
       		$fansSum->value = '1';
       		$fansSum->save();
       } else {
       		$fansSum->value = strval(intval($fansSum->value) + $num);
       		$fansSum->save();
       }
       return $fansSum->value;
	} 

	/**
	 * 加关注
	 * @param unknown $id
	 */
	public function addUserRelation(Request $request){
		$data = array();
		if(Auth::check()){
			$users = Auth::user();
			$id = $request->input('id');
// 			echo $id;die;
			$action = $request->input('action');
			if($action == 'addFollow'){
				// echo $this->FollowSwitch($request);
				$return = $this->FollowSwitch($request);

				echo json_encode($return);
				exit;
				// }
			}else if($action == 'addGive'){
				$gold = $request->input('gold');
				$payResult = GM::payGold($gold, '5000122', $id, $users->id, '打赏'.$gold.'金币');
				$return = [];
				if($payResult['result'] == 1){
					GM::incomeGold($gold,'5000122',$users->id,$id,'被打赏了'.$gold.'金币', true);
					$return['code'] = $payResult['result'];
					$return['msg'] = $payResult['msg'];
				}else{
					$return['code'] = -1;
					$return['msg'] = $payResult['msg'];
					$return['current_gold'] = isset($payResult['current_gold']) ? $payResult['current_gold'] :'';
				}
				echo json_encode($return);
				exit;
			}
		}
	}


	public function showProductInfo($id){
		$userProduction = UserProduction::findOrFail($id);
        $res = $userProduction->convertContent();
        if($res > 0){
            $userProduction = UserProduction::find($id);
        }
        $uid =Auth::check()?Auth::user()->id:0;
		return view('product', ['model'=>$userProduction,'isOwner'=>$uid==$userProduction->user_id]);
	}

	public function ajaxReturn($code,$msg='',$parm = array()){
		$data = array();
		$data['code'] = $code;
		$data['msg'] = $msg;
		$data['parm'] = $parm;
		return json_encode($data);
	}

	/**
	 * 发私信
	 * @param Request $request
	 */
	public function sendPrivateLetter(Request $request){
		$data = array();
		if(Auth::check()){
			$users = Auth::user();
			$id = $request->input('id');
			$msg = $request->input('msg');
			$msg = trim($msg);
			if(empty($msg)|| empty($id)){
				$return = $this->ajaxReturn(-1,'参数错误');
				echo $return;
				exit;
			}
			$data['user_id'] = $id;
			$data['send_id'] = $users->id;
			$data['msg'] = $msg;
			$data['status'] = 'N';
			$new = $this->createUserPrivateLetter($data);
			if($new ->id > 0 ){
				$return = $this->ajaxReturn(1);
				echo $return;
				exit;
			}
		}
	}

	private function createUserPrivateLetter($data){
		return UserPrivateLetter::create([
				'user_id' => $data['user_id'],
				'send_id' => $data['send_id'],
				'msg' => $data['msg'],
				'status' => $data['status']
				]);
	}

	public function loadLikeUserList($obj, $from, $to, $pid)
	{
		$models = LikeModel::with('user')
		->where('resource',$obj)
		->where('resource_id', $pid)
		->orderBy('created_at','desc')
		->skip($from)->take($to-$from+1)->get();
		$list = array();
		foreach ($models as $key => $value) {
			array_push($list, $value->user);
		}

		return view('partview.useritem', array('models'=>$list));
	}
	public function getContributorUserList($from,$to,$pid)
	{
		$res = DB::table('t_ip_contributor')
			->join('t_user','t_user.id','=','t_ip_contributor.user_id')
			->select(DB::raw('count(1) as con_count, t_user.id'))
			->where('t_ip_contributor.ip_id','=',$pid)
			->groupBy('t_user.id')->skip($from)->take($to-$from+1)->get();
		$userIds = array();
		foreach ($res as $key => $value) {
			array_push($userIds, $value->id);
		}
		$list = User::whereIn('id',$userIds)->get();


		return view('partview.useritem', array('models'=>$list));

	}
	public function getMasterUserList($from,$to,$pid=''){
		//推荐的大神
		$userIdList = [];
		$recommendUsers = UserDetailStatus::where('is_expert', true)->orderBy('created_at', 'desc')->skip($from)->take($to - $from + 1)->get();
		foreach ($recommendUsers as $us) {
			array_push($userIdList, $us->user_id);
		}
		$models = User::whereIn('id', $userIdList)->get();
		return view('partview.useritem', array('models'=>$models));
	}
	public function receiveMoney(){
		if(Auth::check()){
			$handel = new CommonHandelGoldController();
			$receive = $handel->commonReceiveMoney();
			echo $receive;
			exit;
		}
	}
	public function createSurvey(){
		$attrs = SysAttrEnum::where('column','>=','20002')->where('column','<=','20005')->get();
		$attr = [];
		foreach ($attrs as $k=>$v){
// 			echo $v->column->name;die;
			$attr[$v['column']]['name'][] = $v['name'];
			$attr[$v['column']]['code'][] = $v['code'];
// 			$attr[$v['column']]['label'] = $v->columns->name;
		}
		// print_r($attr);
		$postUrl   = CommonUtils::getAliUrl('post');
		$accessId  = CommonUtils::getAliOssAccessId();
        $policy    = CommonUtils::getAliOSSPostPolicy();
        $signature = Commonutils::getAliOSSSignature($policy);
        $showUrl   = CommonUtils::getAliUrl('show');
        $uploadAvatarName = CommonUtils::createRandomId('avatar');
		$uploadBackgroundName = CommonUtils::createRandomId('background');

		$id = Auth::user()->id;
		$userinfos = UserAttr::where('user_id',$id)->get();
		$default = array(
			'sex'=>'',
			'merge'=>'',
			'record'=>'',
			'position'=>'',
			'sign'=>'',
			'age'=>'',
		);
		foreach($userinfos as $k=>$v){
			if(strpos($v['attr_code'],'20002')!==false){
				$default['sex'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20003')!==false){
				$default['merge'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20004')!==false){
				$default['record'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20005')!==false){
				$default['position'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20001')!==false){
				$default['age'] = $v['attr_value'];
			}else if(strpos($v['attr_code'],'20006')!==false){
				$default['text'] = $v['attr_value'];
			}
		}

		$arr = [];
		$arr['accessId'] = CommonUtils::getAliOssAccessId();
		$arr['policy'] = CommonUtils::getAliOSSPostPolicy();
		$arr['signature'] = Commonutils::getAliOSSSignature($arr['policy']);
		$arr['postUrl'] = CommonUtils::getAliUrl('post');
		$arr['showUrl'] = CommonUtils::getAliUrl('show');
		$arr['nameSeed'] = Commonutils::createRandomId('role');
		$arr['token'] = Commonutils::createUserToken($id);
		// $arr['token'] = Commonutils::createUserToken('59');
		// $arr['id'] = $roleid;
		// $arr['attrArr'] = $attrArr;
		// $arr['attrCode'] = $attrCode;

		$user = User::find($id);
		$default['avatarPath'] = $user->avatar->getPath(1,'90w_90h_1e_1c');
		$default['backgroundPath'] = $user->backgroundUrl->getPath(1,'90w_90h_1e_1c');
		$default['display_name'] = is_null($user)?'':$user->display_name;

		$arr['attr'] = $attr;
		$arr['default'] = $default;
		return view('partview.user.surveycreate',$arr);
	}

	public function addSurvey(){
		$data['sex'] = Input::get('sex');
		$data['merage'] = Input::get('merage');
		$data['record'] = Input::get('record');
		$data['job'] = Input::get('job');
		$birthday = Input::get('age');
		$msg = CommonUtils::escapeSpecialChars(Input::get('text'));
		$uid = Auth::user()->id;
		if(!empty($msg)){
			UserAttr::create(['user_id'=>$uid,'attr_code'=>'20006','attr_value'=>$msg]);
		}
		UserAttr::create(['user_id'=>$uid,'attr_code'=>'20001','attr_value'=>$birthday]);
		foreach($data as $code){
			$attr = SysAttrEnum::where('code',$code)->first();
			UserAttr::create(['user_id'=>$uid,'attr_code'=>$code,'attr_value'=>$attr['name']]);
		}


		if(Input::get('display_name') || Input::get('avatar') || Input::get('background')){
			$user = User::find($uid);
			$user->display_name = Input::get('display_name');
			$user->avatar = Input::get('avatar');
			$user->background = Input::get('background');
			$user->save();

		}
		$param['redirectUrl'] = '/user/showuserprefrence/cartoon';
		return CommonUtils::ajaxReturn(1, '', $param);
	}

	public function getUserSkill()
	{
		$attrs = SysUserSkill::orderBy('hot')->get()->toArray();
		foreach($attrs as $attr){
            if($code <= 2){
                $code[$attr['code']] = $attr['name'];
            }
		}
		$skill = array(
				array('type'=>'userSkill','name'=>'技能筛选','key'=>$code),//技能筛选
		);
		return response()->json($skill);
	}
	public function showSkill($uid){

		$userSkill = UserSkill::where('user_id', $uid)->get();
		$selectedSkill='';
		foreach ($userSkill as $key => $value) {
			# code...
			$skill = $value->skill;
			$selectedSkill.='_'.$skill->code.'_'.$value->level.'_'.$skill->name.';';
		}
		return view('partview.user.userskillcreate',array('uid'=>$uid,'selectedSkills'=>$selectedSkill));
	}
	public function changeSkill(){
		$uid = Input::get('uid');
		$skill = array_filter(explode(';',Input::get('rulesList')));
		foreach ($skill as $k=>$v){
			$rules = explode('_',$v);
			$rule[$rules[0]][$rules[1]] = Input::get('rule_'.$rules[1]);
			switch ($rules[0]){
				case 'userSkill':
					$code[]= $rules[1];
					$level[$rules[1]]= Input::get('rule_'.$rules[1]);
					continue;
			}
		}
		//改变后的技能
		$skillsChange = [];
		if(!empty($level)){
			foreach($level as $k=>$v){
				$user = UserSkill::firstOrCreate(['user_id'=>$uid,'code'=>$k]);
				$user->level = $v;
				$user->save();
				array_push($skillsChange, $k);
				// UserSkill::create(['user_id'=>$uid,'code'=>$k,'level'=>$v]);
			}
		}

		$sqlUserSkills = UserSkill::where('user_id', $uid)->lists('code');
		//数据库中的技能
		$skillsChangeAfter = [];
		foreach ($sqlUserSkills as $key => $value) {
			# code...
			array_push($skillsChangeAfter, $value);
		}

		//获取要删除的技能
		$skillsDiff = array_diff($skillsChangeAfter, $skillsChange);
		if (!empty($skillsDiff)) {
			# code...
			foreach ($skillsDiff as $key => $value) {
				# code...
				$userSkill = UserSkill::where("user_id",$uid)->where("code",$value)->first();
				$userSkill->delete();
			}
		}

		return redirect('/reshall');
	}

	public function showEditUser($id){
		$attrs = SysAttrEnum::where('column','>=','20001')->where('column','<=','20005')->get();
		$attr = [];
		foreach ($attrs as $k=>$v){
			// 			echo $v->column->name;die;
			$attr[$v['column']]['name'][] = $v['name'];
			$attr[$v['column']]['code'][] = $v['code'];
			// 			$attr[$v['column']]['label'] = $v->columns->name;
		}
		$attrs = UserAttr::where('user_id',$id)->get();
		$default = array(
			'sex'=>'',
			'merge'=>'',
			'record'=>'',
			'position'=>'',
			'sign'=>'',
			'age'=>'',
		);
		foreach($attrs as $k=>$v){
			if(strpos($v['attr_code'],'20002')!==false){
				$default['sex'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20003')!==false){
				$default['merge'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20004')!==false){
				$default['record'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20005')!==false){
				$default['position'] = $v['attr_code'];
			}else if(strpos($v['attr_code'],'20006')!==false){
				$default['sign'] = $v['attr_value'];
			}else if(strpos($v['attr_code'],'20001')!==false){
				$default['age'] = $v['attr_value'];
			}
		}
		$userSkill = UserSkill::where('user_id',$id)->get();
		$default['userSkillLevel'] = '';
		foreach ($userSkill as $k=>$v){
			$default['userSkill'] = ';userSkill_'.$v['code'];
			$sysUserSkill = SysUserSkill::where('code',$v['code'])->first();
			$default['userSkillLevel'] .= ';userSkill_'.$v['code'].'_'.$v['level'].'_'.$sysUserSkill['name'];
		}
		$user = User::find($id);
		$default['avatarPath'] = $user->avatar->getPath(1,'90w_90h_1e_1c');
		$default['backgroundPath'] = $user->backgroundUrl->getPath(1,'90w_90h_1e_1c');
		$default['display_name'] = is_null($user)?'':$user->display_name;
		//return view('partview.user.usereditinfo',array('id'=>$id,'user'=>Auth::user(),'attr'=>$attr,'default'=>$default));
		$arr = Image::getUploadAliImageParams();
		$arr['id'] = $id;
		$arr['user'] = Auth::user();
        $arr['attr'] = $attr;
        $arr['default'] = $default;
		return view('uset.mainpage',$arr);
	}

	public function editUserInfo(){
		$id = Input::get('id');
		$user = User::find($id);
		$user->display_name = trim(Input::get('display_name'));
		$user->avatar = Input::get('avatar');
		$user->background = Input::get('background');
		$user->save();
		$data['sex'] = Input::get('sex');
		$data['merage'] = Input::get('merage');
		$data['record'] = Input::get('record');
		$data['job'] = Input::get('job');
		$msg = CommonUtils::escapeSpecialChars(Input::get('text'));
		$sign = UserAttr::where('user_id',$id)->where('attr_code','20006')->first();
		if($sign){
			$sign->attr_value = $msg;
			$sign->save();
		}else{
			UserAttr::create(['user_id'=>$id,'attr_code'=>'20006','attr_value'=>$msg]);
		}
		$age = UserAttr::where('user_id',$id)->where('attr_code','20001')->first();
		$birthday = Input::get('age');
		if(empty($age)){
			UserAttr::create(['user_id'=>$id,'attr_code'=>'20001','attr_value'=>$birthday]);
		}else{
			$age->attr_value = $birthday;
			$age->save();
		}
		foreach($data as $code){
			if($code){
				$attr = SysAttrEnum::where('code',$code)->first();
				if(strpos($code,'20002')!==false){
					$attrs = UserAttr::where('user_id',$id)->where('attr_code','like','%20002%')->first();
				}else if(strpos($code,'20003')!==false){
					$attrs = UserAttr::where('user_id',$id)->where('attr_code','like','%20003%')->first();
				}else if(strpos($code,'20004')!==false){
					$attrs = UserAttr::where('user_id',$id)->where('attr_code','like','%20004%')->first();
				}else if(strpos($code,'20005')!==false){
					$attrs = UserAttr::where('user_id',$id)->where('attr_code','like','%20005%')->first();
				}
				if(empty($attrs)){
					UserAttr::create(['user_id'=>$id,'attr_value'=>$attr->name,'attr_code'=>$code]);
				}else{
					$attrs->attr_value = $attr->name;
					$attrs->attr_code = $code;
					$attrs->save();
				}
			}
		}
		$skill = array_filter(explode(';',Input::get('rulesList')));
		$userSkill = UserSkill::where('user_id',$id)->get()->toArray();
		$skillCode = [];
		$tagCode = [];
		foreach ($userSkill as $key => $tag) {
			$tagCode[] = $tag['code'];
			$tagKey[$tag['code']]  = $tag['id'];
		}
		foreach ($skill as $k=>$v){
			$rules = explode('_',$v);
			$rule[$rules[0]][$rules[1]] = Input::get('rule_'.$rules[1]);
			switch ($rules[0]){
				case 'userSkill':
					$skillCode[]= $rules[1];
					$level[$rules[1]]= Input::get('rule_'.$rules[1]);
					continue;
			}
		}
		$skillAll = array_unique(array_merge($tagCode, $skillCode));
		foreach ($skillAll as $key => $tag) {
			if (in_array($tag, $skillCode) && !in_array($tag, $tagCode)) {
				UserSkill::create(['user_id'=>$id,'code'=>$tag,'level'=>$level[$tag]]);
			} else if (!in_array($tag, $skillCode) && in_array($tag, $tagCode)) {
				UserSkill::find($tagKey[$tag])->delete();
			} else if (in_array($tag, $skillCode) && in_array($tag, $tagCode)) {
				$userSkillAttr = UserSkill::find($tagKey[$tag]);
				$userSkillAttr->level = $level[$tag];
				$userSkillAttr->save();
			}
		}
// 		$return = CommonUtils::ajaxReturn(1,'',array('redirectUrl'=>'/home/list/default/0/' . $id));
		return redirect('/home/list/default/0/' . $id);
	}
}
?>
