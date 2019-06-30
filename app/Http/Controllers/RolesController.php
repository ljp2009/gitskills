<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonLikeController;

use App\Models\Ip;
use App\Models\IpRole;
use App\Models\IpRoleSkill;
use App\Models\ListItem;
use App\Models\SysAttrEnum;

use App\Common\CommonUtils;
use App\Common\GoldManager;
use App\Common\OwnerHandler;
use App\Common\IpContributorHandler;

use Auth, Redirect, Input;

class RolesController extends Controller
{
	protected $RESOURCE_NAME_ROLE = 'role';
    //角色列表页面
	public function loadRoleList( $page, $id) {
        return view('detaillist', array('title'=>'角色列表',
            'type'=>'roles','id'=>$id,
            'listName'=>'default','page'=>$page));
	}
    //角色列表数据
	public function loadRoleListData( $from, $to, $id) {
		$models = array();
        $result = IpRole::where('ip_id', $id)
            ->orderBy('like_sum', 'desc')
            ->orderBy('id', 'desc')
            ->skip($from)
            ->take($to-$from+1)
            ->get();
        $items = ListItem::makeRoleListItem($result);
		return view('partview.detaillistitem', array('models'=>$items));
	}
    //角色详细页面
	public function showDetail($id)
	{
		$ipRole = IpRole::findOrFail($id);
		$roleItem =ListItem::makeRoleListItem([IpRole::findOrFail($id)])[0];
		return view('rolesdetail', array('id'=>$id,
			'model'       => $ipRole,
			'listItem'    => $roleItem,
			'isOwner'     => OwnerHandler::checkByObj('ip_role', $ipRole),
			'deleteRoute' => '/common/discuss/delete'));
	}
    //角色页面延迟加载
	public function loadPartview($id,$partview)
	{
		switch ($partview) {
			case 'roles':
				return $this->getRoleDetailPartview($id);
			case 'skill':
				return $this->getRoleSkillPartview($id);
			default:
				return '';
		}
	}
	private function getRoleDetailPartview($id)
	{
		$ipRole = IpRole::findOrFail($id);
		return view('partview.roles.photo', array('models'=>$ipRole));
	}
	private function getRoleSkillPartview($id)
	{
		$ipRole = IpRole::find($id);
		$rolesSkill = IpRoleSkill::where('role_id', $id)->orderBy('is_main', 'desc')->get();
		return view('partview.roles.skill',array(
			'models'=>$rolesSkill,
			'isOwner'=>OwnerHandler::checkByObj('ip_role', $ipRole),
			'id'=>$id));
	}

	//创建角色
	public function displayCreateRoles($id){
		$showUrl = CommonUtils::getAliUrl('show');
		 $params = [
            'title' =>'创建角色',
            'description'=> '',
            'id'=>0,
            'fields' =>[
                'pid'=>['value'=>$id],
                'title' => ['value'=>'', 'placeholder'=>'角色名称', 'max'=>'50'],
                'content' => ['value'=>'', 'placeholder'=>'角色介绍', 'max'=>'1000'],
                'addAvater' => ['value'=>'',  'require'=>'require'],
                'addImg' => ['value'=>'',  'require'=>'require'],
            ],
            'postUrl' => '/roles/create',
            'showUrl' => $showUrl,
        ];
		return view("rolespubpage", $params);
		
	}
	//提交角色信息
	public function addRoles(){

        $userId = Auth::id();
        $payRes = GoldManager::publishPayGold('ip_role', 0, $userId);
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
        $model = new IpRole;
        $model->header = CommonUtils::evalPics(Input::get('addAvater'));
        $model->image = CommonUtils::evalPics(Input::get('addImg'));
        $model->ip_id= intval(Input::get('pid'));
        $model->name= Input::get('title');
        $model->creator= $userId;
        $model->user_id= $userId;
        $model->is_lock= false;
        $model->mender= $userId;
        
        $model->intro = CommonUtils::escapeSpecialChars($_POST['content']);
        $model->save();
        IpContributorHandler::SaveIpContributor(intval(Input::get('pid')), $userId, $model->id, 'ip_role');

		// return redirect(noBackUrl('/roles/'.$model->id));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/iprole_'.$model->id]);
	}
	//编辑角色
	public function displayEditRoles($id){

		$ipRole  = IpRole::findOrFail($id);
        if(!self::checkRoleAuthority($ipRole)){
            return errorpage();
        }
		$showUrl = CommonUtils::getAliUrl('show');
		$params = [
            'title' =>'编辑角色',
            'description'=> '',
            'id'=>$ipRole->id,
            'fields' =>[
                'pid'=>['value'=>$ipRole->ip_id],
                'title' => ['value'=>$ipRole->name, 'placeholder'=>'角色名称', 'max'=>'50'],
                'content' => ['value'=>$ipRole->intro, 'placeholder'=>'角色介绍', 'max'=>'1000'],
                'addAvater' => ['value'=>$ipRole->header,  'require'=>'require'],
                'addImg' => ['value'=>$ipRole->image,  'require'=>'require'],

            ],
            'postUrl' => '/roles/edit',
            'showUrl' => $showUrl,
        ];
		return view("rolespubpage", $params);
	}
	//提交编辑信息
	public function editRoles(){
        $userId = Auth::user()->id;
        $model = IpRole::findOrFail(Input::get('id'));
        if(!self::checkRoleAuthority($model)){
            return errorpage();
        }
        $model->header = CommonUtils::evalPics(Input::get('addAvater'));
        $model->image  = CommonUtils::evalPics(Input::get('addImg'));
        $model->ip_id  = intval(Input::get('pid'));
        $model->name   = Input::get('title');
        $model->mender = $userId;
        $model->intro  = CommonUtils::escapeSpecialChars(Input::get('content'));
        $model->save();
        //return redirect('/roles/'.Input::get('id'));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/iprole_'.$model->id]);
	}

	public function displayCreateRoleSkill($roleId){
        if(!self::checkRoleAuthority($roleId)){
            return errorpage();
        }
		$attr = SysAttrEnum::where('column','30001')->get()->toArray();
		$attrArr = array('0'=>'选择技能属性');
		$attrCode = array(0=>'0');
		foreach($attr as $k=>$v){
			array_push($attrArr,$v['name']);
			array_push($attrCode,$v['code']);
		}
		$arr = [];
		$arr['id'] = $roleId;
		$arr['attrArr'] = $attrArr;
		$arr['attrCode'] = $attrCode;
		$arr['title'] = '发布角色技能';

		return view("partview.roles.roleskillcreate", $arr);
	}

	public function addRoleSkill(){
        $roleId = Input::get('role_id');
        if(!self::checkRoleAuthority($roleId)){
            return errorpage();
        }
        $userId = Auth::user()->id;
        $model = new IpRoleSkill;
        $model->role_id= intval($roleId);
        $model->name= Input::get('name');
        $model->skill_type= Input::get('attrcode');
        $model->image = CommonUtils::evalPics(Input::get('addImg'));
        $model->intro = CommonUtils::escapeSpecialChars(Input::get('intro'));
        $model->save();
		return redirect('/roles/'.$model->role_id);
	}

	public function displayEditRoleskill($id){
		$model = IpRoleSkill::find($id);
        if(!self::checkRoleAuthority($model->role_id)){
            return errorpage();
        }
		$attr = SysAttrEnum::where('column','30001')->get()->toArray();
		$attrArr = array('0'=>'选择技能属性');
		$attrCode = array(0=>'0');
		foreach($attr as $k=>$v){
			array_push($attrArr,$v['name']);
			array_push($attrCode,$v['code']);
		}
		$arr = [];
		$arr['id'] = $id;
		$arr['attrArr'] = $attrArr;
		$arr['attrCode'] = $attrCode;
		$arr['title'] = '编辑角色技能';
		$arr['model'] = $model;

		return view("partview.roles.roleskilledit", $arr);

	}
	public function editRoleskill(){
		$id = Input::get('id');

		$model = IpRoleSkill::find($id);
        if(!self::checkRoleAuthority($model->role_id)){
            return errorpage();
        }
		$model->name = CommonUtils::escapeSpecialChars(Input::get('name'));
		$model->intro = CommonUtils::escapeSpecialChars(Input::get('intro'));
		$model->header = CommonUtils::evalPics(Input::get('header'));
		$model->skill_type= Input::get('attrcode');
		$model->save();
		return redirect('/roles/'.$model->role_id);
	}
	public function deleteRole(){
		$roleId = Input::get("id");
		$role = IpRole::findOrFail($roleId);
        if(!self::checkRoleAuthority($role)){
            $re = 'false';
        }else{
			$skills = IpRoleSkill::where('role_id', $roleId)->delete();
			$role->delete();	
            $re = 'true';
        }
		return response()->json(['res'=>$re]);
	}
	public function deleteSkill(){
		$skillId = Input::get("id");
		$skill = IpRoleSkill::findOrFail($skillId);
		if(self::checkRoleAuthority($skill->role_id)){
			$skill->delete();	
			return $skillId;
		}else{
			return 'false';
		}	
	}
	public function setSkill(){
		$skillId = Input::get("id");
		$skill = IpRoleSkill::findOrFail($skillId);
		if(self::checkRoleAuthority($skill->role_id)){
			$roleId = $skill->role->id;
			$mainSkill = IpRoleSkill::where('role_id', $roleId)->where('is_main',true)->update(['is_main'=>false]);
			$skill->is_main =true;
			$skill->save();
			return $skillId;
		}else{
			return 'false';
		}	
	}
    private static function checkRoleAuthority($input){
        $role = null;
        if(is_numeric($input)){
            $role = IpRole::findOrFail($input);
        }else{
            $role = $input;
        }
        return OwnerHandler::checkByObj('ip_role', $role);
    }
}
?>
