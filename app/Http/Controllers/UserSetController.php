<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\UserAttr;
use App\Models\UserSkill;
use App\Models\UserInfo;
use App\Models\SysAttrEnum;
use App\Common\Image;
use App\Common\Enums;
use App\Common\VCodeHandler;
use App\Common\WechatHandler;
use Auth, Redirect, Input,DB;
class UserSetController extends Controller
{
    public function mainIndex() {
        $arr = Image::getUploadAliImageParams();
        $arr['user'] = Auth::user();
        return view('uset.mainpage', $arr);
    }
    public function saveDisplayName(){
        $user = User::find(Auth::id());
        $displayName = Input::get('display_name');
        if($user->display_name != $displayName){
            $user->display_name = $displayName;
            $user->save();
        }
        return response()->json(['res'=>true]);
    }
    public function saveSignature(){
        $user = User::find(Auth::id());
        $signature = Input::get('signature');
        $userInfo = $user->userInfo;
        if($userInfo->sign != $signature){
            $userInfo->sign = $signature;
            $userInfo->save();
        }
        return response()->json(['res'=>true]);
    }
    public function attrIndex(){
        $user = User::find(Auth::id());
        $arr  = [];
        $arr['info']  = $user->userInfo;
        $arr['items'] = [];
        $arr['items']['sex']       = Enums::getItems('sex');
        $arr['items']['marriage']  = Enums::getItems('marriage');
        $arr['items']['education'] = Enums::getItems('education');
        $arr['items']['job']       = Enums::getItems('job');
        return view('uset.attrpage', $arr);
    }
    public function saveAttr(Request $request){
		$uid = Auth::id();
        $userInfo = UserInfo::where('user_id', $uid)->first();
        $userInfo->birthday = $request['birthday'];
        $userInfo->sex = $request['sex'];
        $userInfo->marriage = $request['marriage'];
        $userInfo->education = $request['education'];
        $userInfo->job = $request['job'];
        $userInfo->save();
        return response()->json(['res'=>true]);
    }

	public function skillIndex(){
        $uid = Auth::id();
        $userSkill = UserSkill::where('user_id', $uid)->get();
        $selectedSkill='';
        foreach ($userSkill as $key => $value) {
            # code...
            $skill = $value->skill;
            $selectedSkill.='_'.$skill->code.'_'.$value->level.'_'.$skill->name.';';
        }
        return view('uset.skillpage',array('uid'=>$uid,'selectedSkills'=>$selectedSkill));
    }
    public function saveSkill(){
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

        return redirect('/page/loading/back_2');
    }

    //--技能设定新接口，暂未使用
    public function loadSkill(){
        $uid = Auth::id();
        $userSkill = UserSkill::where('user_id', $uid)->get();
        $selectedSkill='';
        foreach ($userSkill as $key => $value) {
            # code...
            $skill = $value->skill;
            $selectedSkill.='_'.$skill->code.'_'.$value->level.'_'.$skill->name.';';
        }
    }
    public function addSkill(Request $request){
        $code = $request['code'];
        $userId = Auth::id();
        $userSkill = UserSkill::where('user_id', $userId)
            ->where('code', $code)->first();
        if(is_null($userSkill)){
            $userSkill = new UserSkill;
            $userSkill->user_id = $userId;
            $userSkill->code = $code;
            $userSkill->level = 1;
            $userSkill->score = 0;
            $userSkill->save();
        }
        $obj = [
            'code'  => $userSkill->code,
            'level' => $userSkill->level,
            'name'  => $userSkill->name
        ];
        return response()->json([ 'res' => true, 'info' => $obj ]);
    }
    public function updateSkill(){
        $code  = $request['code'];
        $level = $request['level'];
        $userId = Auth::id();
        $userSkill = UserSkill::where('user_id', $userId)
            ->where('code', $code)->first();
        $userSkill->level = $level;
        $userSkill->save();
        $obj = [
            'code'  => $userSkill->code,
            'level' => $userSkill->level,
            'name'  => $userSkill->name
        ];
        return response()->json([ 'res' => true, 'info' => $obj ]);
    }
    public function removeSkill(){
        $code  = $request['code'];
        $level = $request['level'];
        $userId = Auth::id();
        $userSkill = UserSkill::where('user_id', $userId)
            ->where('code', $code)->first();
        $userSkill->delete();
        return response()->json([ 'res' => true, 'info' => $code]);
    }
    //--新接口结束
    public function saveAvatar(){
		$user = Auth::user();
        $fileName = trim(Input::get('fileName'));
        $user->avatar = $fileName;
        $user->save();
        return response()->json(['res'=>true, 'info'=>$user->avatar->getPath(2)]);
    }
    public function saveBackground(){
		$user = Auth::user();
        $fileName = Input::get('fileName');
        $user->background = $fileName;
        $user->save();
        return response()->json(['res'=>true, 'info'=>$user->background->getPath(2)]);
    }

    public function mobileIndex(){
		$user = Auth::user();
       return view('uset.bindphone', ['oldValue'=>$user->mobile]);
    }
    public function saveMobile($type){
        if(!in_array($type, ['bind', 'unbind'])){
            return response()->json(['res'=>false,'info'=>'无效操作。']);
        }
        if($type == 'bind'){
            $value = Input::get('value');
            $code = Input::get('code');
            if(!VCodeHandler::checkCode('mobile', $value, $code, 'bind')){
                return response()->json(['res'=>false,'info'=>'验证码无效。']);
            }
        }else{
            $value = '';
        }

        $user = Auth::user(); 
        $password = Input::get('password');
        if($user->password == ''){
            if($password != '11111111'){
                return response()->json(['res'=>false,'info'=>'密码错误。']);
            }
        }else{
            if(!Auth::attempt(['id'=>$user->id, 'password'=>$password], false, false)){
                return response()->json(['res'=>false,'info'=>'密码错误。']);
            }
        }
        $user->mobile = $value;
        $user->save();
        return response()->json(['res'=>true]);

    }

    public function emailIndex(){
		$user = Auth::user();
       return view('uset.bindemail', ['oldValue'=>$user->email]);
    }
    public function saveEmail($type){
        if(!in_array($type, ['bind', 'unbind'])){
            return response()->json(['res'=>false,'info'=>'无效操作。']);
        }
        if($type == 'bind'){
            $value = Input::get('value');
            $code = Input::get('code');
            if(!VCodeHandler::checkCode('email', $value, $code, 'bind')){
                return response()->json(['res'=>false,'info'=>'验证码无效。']);
            }
        }else{
            $value = '';
        }
        $user = Auth::user(); 
        $password = Input::get('password');
        if($user->password == ''){
            if($password != '11111111'){
                return response()->json(['res'=>false,'info'=>'密码错误。']);
            }
        }else{
            if(!Auth::attempt(['id'=>$user->id, 'password'=>$password], false, false)){
                return response()->json(['res'=>false,'info'=>'密码错误。']);
            }
        }
        $user->email = $value;
        $user->save();
        return response()->json(['res'=>true]);
    }
    public function wechatBind(){
        $url = WechatHandler::getWechatOAuthUrl(1);
        return redirect($url);
    }
    public function wxRegistCallback(Request $request){
        $code = $request->input('code');
        //未接受授权
        if(!$code){
            return false;
        }
        $bx = WechatHandler::getToken($code);
        if(is_null($bx)){
            return false;
        }
        $openid = $bx['openid'];
        $token = $bx['access_token'];
        $rtoken = $bx['refresh_token'];

        //检查用户绑定
        $user = $this->getUserByWxOpenid($openid);
        if(!is_null($user)){
            //如果已经绑定过了就直接登录
            Auth::login($user);
            return redirect('/reshall');
        }else{
            $wxUser = WechatHandler::getUserInfo($token, $openid); 
            if(is_null($wxUser)) return false;
            //Register User
            $user = new User;
            $user->$type = $value;
            $user->display_name = $wxUser['nickname'];
            $user->password = bcrypt($password);
            $user->avatar = $wxUser['headimgurl'];
            $user->save();
            // 注册成功
            Auth::login($user);
            return redirect('/reshall');
        }
    }
    public function getPwdIndex(){
        return view('uset.password');
    }
    public function setPwd(){
        $oldPwd = Input::get('oldPwd');
        $newPwd = Input::get('newPwd');
        $user = User::find(Auth::id());
        if($user->password == ''){
            if($oldPwd != '11111111'){
                return response()->json(['res'=>false,'info'=>'密码验证失败。']);
            }
        }else{
            if(!Auth::attempt(['id'=>$user->id, 'password'=>$oldPwd], false, false)){
                return response()->json(['res'=>false,'info'=>'密码验证失败。']);
            }
        }
        $user->password = bcrypt($newPwd);
        $user->save();
        return response()->json(['res'=>true,'info'=>'']);
    }
}
