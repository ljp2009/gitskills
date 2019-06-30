<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Common\CommonScoreController;
use App\Http\Controllers\Common\CommonDiscussionController;
use App\Http\Controllers\Common\CommonLikeController as LikeCtrl;
use App\Models\Ip;
use App\Models\IpRole;
use App\Models\IpIntro;
use App\Models\IpScene;
use App\Models\IpDialogue;
use App\Models\IpColleague;
use App\Models\IpPeripheral;
use App\Models\IpAttr;
use App\Models\IpTag;
use App\Models\IpContributor;
use App\Models\IpSum;
use App\Models\Dimension;
use App\Models\User;
use App\Models\IpUserStatus;
use App\Models\LikeModel;
use App\Models\LikeSumModel;
use App\Models\SysAttr;
use App\Models\SysTag;
use App\Models\UserProduction;
use App\Common\IpContributorHandler;
use App\Common\GoldManager;
use DB, Auth, Input;
class IpController extends Controller
{
	public function index($id) {
		$scoreController = new CommonScoreController;
		$scoreResult = $scoreController->getUserScore('ip', $id);
		$ip = Ip::findOrFail($id);
		if(!$ip->validated) {
		}
		$userStatus = '';
		if(Auth::check()) {
			$status = IpUserStatus::where('user_id',Auth::user()->id)
			->where('ip_id', $id)->get();
			if($status->count()== 0) {
				$userStatus = 'reading';
			}
			else {
				$userStatus = $status[0]->status;
			}
		}
		return view('ipdetail',
			array('id'=>$id, 'model'=>$ip,
			'score'=>$scoreResult['score'],
			'scoreId'=>$scoreResult['id'],
			'userStatus'=>$userStatus,
			'deleteDiscussRoute'=>'/common/discuss/delete'
		));
	}
	public function loadPartview($id,$partview) {
		$fun = 'load'.studly_case($partview);
		return $this->$fun($id);
	}
	private function loadRoles($id) {
		$ipRoles = IpRole::where('ip_id', '=', $id)->orderBy('like_sum','desc')->orderBy('id')->take(5)->get();
		return view('partview.ip.roles',array('roles'=>$ipRoles,'id'=>$id));
	}
	private function loadIntro($id) {
		$intro = IpIntro::where('ip_id', '=', $id)->first();
		return view('partview.ip.intro',array('intro'=>is_null($intro)?'':$intro->intro));
	}
    private function loadScene($id){
        $items = IpScene::where('ip_id', $id)->orderBy('like_sum','desc')->orderBy('created_at','desc')->take(1)->get();
        return view('partview.ip.scene', ['items'=>$items, 'ipid'=>$id]);
    }

    private function loadDialogue($id){
        $items = IpDialogue::where('ip_id', $id)->orderBy('like_sum','desc')->orderBy('created_at','desc')->take(3)->get();
        return view('partview.ip.dialogue', ['items'=>$items, 'ipid'=>$id]);
    }
    private function loadDiscussion($id){
        $items = UserProduction::where('ip_id', $id)
            ->where('relate_type', 'disc')
            ->orderBy('like_sum', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(2)->get();
        return view('partview.ip.discussion', ['items'=>$items, 'ipid'=>$id]);
    }
	private function loadExpert($id)
	{
        $likeSum = LikeSumModel::countLike('ip', $id);
        //$likeCount = is_null($likeSum)?0:$likeSum->like_sum;
        $likeCount = $likeSum;
        $likes = LikeModel::where('resource','ip')
            ->where('resource_id', $id)
            ->orderBy('created_at', 'desc')->take(6)->get();
        $idArr =[];
        foreach($likes as $lk){
            array_push($idArr, $lk->user_id);
        }
        $users = User::whereIn('id', $idArr)->get();
		return view('partview.ip.expert', ['likeCount'=>$likeCount, 'users'=>$users]);
	}
    private function loadColl($id){
        $items = UserProduction::where('ip_id', $id)->where('relate_type','coll')->orderBy('like_sum', 'desc')->take(4)->get();
		return view('partview.ip.related',['items'=>$items, 'type' => 'coll']);
    }
    private function loadPeri($id){
        $items = UserProduction::where('ip_id', $id)->where('relate_type','peri')->orderBy('like_sum', 'desc')->take(4)->get();
		return view('partview.ip.related',['items'=>$items]);
    }
    private function loadDim($id){
		$items = Dimension::where('ip_id',$id)->take(4)->get();
		return view('partview.ip.related',['items'=>$items]);
    }
	private function loadRelated($id)
	{
		//$colleagues = IpColleague::where('ip_id','=',$id)->take(5)->get();
        $colleagues = UserProduction::where('ip_id', $id)->where('relate_type','coll')->take(4)->get();
        $peripherals= UserProduction::where('ip_id', $id)->where('relate_type','peri')->take(3)->get();
		$dimensions = Dimension::where('ip_id',$id)->take(3)->get();
		return view('partview.ip.related',array(
			'pid'=>$id,
			'colls'=>$colleagues,
			'peris'=>$peripherals,
			'dimes'=>$dimensions
			));
	}

	public  function changeIpUserStatus()
	{
 		if(Auth::check()){

 			$pid = Input::get('pid');
 			$status = Input::get('status')=='reading'?'reading':'readed';
 			$currentUser = Auth::user();
 			//return true;
 			$set = IpUserStatus::where('ip_id', $pid)->where('user_id',$currentUser->id)->first();
 			if(is_null($set))
 			{
 				IpUserStatus::create([
 					'user_id'=>$currentUser->id,
 					'ip_id'=>$pid,
 					'status'=>$status
 					]);
 			}
 			else
 			{
 				$set->status = $status;
 				$set->save();
 			}

            return 'true';
        }
        return 'false';
	}

	public function getSysIpAttrs()
	{
		$depend = Input::get('depend');
		$attrs = SysAttr::IpAttrs()->where('depend',$depend)->orderBy('sort')->get()->toArray();
// 		print_r($attrs);
		$attrArray = array();
		foreach ($attrs as $attr) {
			array_push($attrArray, array('key'=>$attr['code'], 'type'=>$attr['data_type'], 'name'=>$attr['name']));
		}
		return response()->json($attrArray);
	}
	public function getSysIpTags()
	{
		$depend = Input::get('depend');
		$tagDepend[] = 'ip';
		$tagDepend[] = $depend;
		$tags = SysTag::whereIn('depend',$tagDepend)->orderBy('hot','desc')->get();

		$tagArray = array();
		foreach ($tags as $tag) {
			array_push($tagArray, array('key'=>$tag->code, 'hot'=>$tag->hot, 'name'=>$tag->name,'depend'=>$tag['depend']));
		}
		return response()->json($tagArray);
	}
	public function displayCreatePage()
	{
		return view('ip.ipcreate', ['title'=>'添加新作品']);
	}
	public function displayNewCreatePage($ipType)
	{
        $title = '添加';
        switch($ipType){
        case 'cartoon':
            $title .= '动漫';
            break;
        case 'story':
            $title .= '小说';
            break;
        case 'light':
            $title .= '轻小说';
            break;
        case 'game':
            $title .= '游戏';
            break;
        }
        return view('ip.newipcreate', ['title'=>$title, 'ipType'=>$ipType]);
	}

	public function addNew()
	{
		$userId = Auth::user()->id;
        $payRes = GoldManager::publishPayGold('ip', 0, $userId);
        $name = Input::get('ipname');
        $type = Input::get('iptype');
        if($type == 'cartoon'){ //检查卡通类别的ip重名
            $existIp = Ip::where('name', $name)->where('type','cartoon')->first();
            if(!is_null($existIp)){
                return response()->json(['res'=>false, 'info'=>'这个动画已经存在了，您可以通过查找得到。', 'url'=>'' ]);
            }
        }
        if(!$payRes){
            return response()->json(['res'=>false, 'info'=>'金币不足，无法创建', 'url'=>'' ]);
        }
		//Create Ip
		 $ip = Ip::create(['name'=>Input::get('ipname'),
		 	'type'=>Input::get('iptype'),
		 	'cover'=>Input::get('cover'),
            'creator'=>$userId,
            'user_id'=>$userId,
        ]);
        $ipId = $ip->id;
		//Create ipIntro
		IpIntro::create(['ip_id'=>$ipId, 'intro'=>Input::get('intro')]);
		//Create ip attrs;
		$attrsList = explode(';', input::get('attrsList'));
		foreach ($attrsList as $key => $attr) {
			if($attr != '' && Input::get('_attr_'.$attr) != '')
			{
				IpAttr::create(['ip_id'=> $ipId, 'code'=>$attr, 'value'=>Input::get('_attr_'.$attr)]);
			}
			if($attr == '10002' && Input::get('_attr_'.$attr.'_status') != ''){
				IpAttr::create(['ip_id'=> $ipId, 'code'=>$attr, 'value'=>Input::get('_attr_'.$attr.'_status')]);
			}
			if($attr == '10009' && Input::get('_attr_'.$attr.'_status') != ''){
				IpAttr::create(['ip_id'=> $ipId, 'code'=>$attr, 'value'=>Input::get('_attr_'.$attr.'_status')]);
			}
		}
		//Create ip Tags;
		$tagsList = explode(';', input::get('tagsList'));
        $tags = SysTag::whereIn('code', $tagsList)->get();
        if(count($tags) > 0){
            $tagsArr = [];
            foreach ($tags as $key => $value) {
                if($value != '')
                {
                    IpTag::create(['ip_id'=>$ipId, 'tag_id'=>$value->code]);
                    array_push($tagsArr, $value->name);
                }
            }
            $ip->tags = $tagsArr;
            $ip->save();
        }
		IpSum::create(['ip_id'=>$ipId, 'code'=>'11001','value'=>'0']);//贡献者数量
        IpSum::create(['ip_id'=>$ipId, 'code'=>'11002','value'=>'0']);//推荐次数
        IpSum::create(['ip_id'=>$ipId, 'code'=>'11003','value'=>'0']);//达人喜欢次数

        // IpContributor::create(['ip_id'=>$ipId, 'user_id'=>$userId, 'type'=>'ip','obj_id'=>$ipId]);
        IpContributorHandler::SaveIpContributor($ipId, $userId, $ipId, 'ip');
        //return  redirect(nobackurl('ip/'.$ipId));
        return response()->json([ 'res'=>true, 'info'=>'', 'url'=>'/page/loading/ip_'.$ipId]);
	}
	
    //IP贡献列表
	public function loadUserListPage($page, $ipid=''){

        $params = ['page'=>$page, 'pid'=>$ipid];

		return view('ipuserlist', $params);
	}

	public function loadUserListData( $from, $to, $ipid=''){

		$items = IpContributor::where('ip_id', $ipid)->orderBy('updated_at')
			->groupBy('user_id')->skip($from)->take($to - $from + 1)->get();
        $arr = [];
        foreach($items as $item){
            array_push($arr, $item->user);
        }

		return view('partview.useritem' ,['models'=>$arr]);
	}
}
?>
