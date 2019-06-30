<?php

namespace App\Http\Controllers;

use App\Common\CommonUtils;
use App\Http\Controllers\Common\CommonLikeController;
use App\Http\Controllers\Common\CommonScoreController;
use App\Http\Controllers\Controller;
use App\Models\Discussion;
use App\Models\Ip;
use App\Models\IpColleague;
use App\Models\IpScene;
use App\Models\IpPeripheral;
use App\Models\IpUserStatus;
use App\Models\IpContributor;
use App\Models\LikeModel;
use App\Models\SysAttrEnum;
use App\Models\User;
use App\Models\UserGoldRecord;
use App\Models\UserProduction;
use App\Models\UserRelation;
use App\Models\UserAttr;
use Auth;
use Input;
use Redirect,DB;
use App\Common\Image;

class HomeController extends Controller
{
    protected $RESOURCE_NAME_DIALOGUE = 'user_production';
    /*
     * 显示用户首页
    * */
    public function showLikeList($listName = 'default', $page, $id)
    {
        $btnClass = [];
        $btnClass['follow'] = 'nodisable';
        $btnClass['msg'] = 'sendMsg';
        $btnClass['give'] = 'nogive';
        $isOwner = false;

        $follow = '关注';
        if (Auth::check()) {
            $curUser = Auth::user();
            $isOwner = ($id == $curUser->id);
            if ($isOwner) {
                $btnClass['follow'] = 'am-disabled';
                $btnClass['msg'] = 'msgList';
                $btnClass['give'] = 'am-disabled';
            }
            $relation = UserRelation::where('user_id', $curUser->id)->where('follow_id', $id)->first();
            if ($relation) {
                $follow = '取消';
            }
        }
        $user = User::findOrFail($id);
        $result = LikeModel::where('user_id', $id)->where('resource', 'ip')->count();
        $view   = ($listName == 'default') ? 'userhome' : 'user' . $listName;

        $arr = Image::getUploadAliImageParams();
        $arr['id'] = $id;
        $arr['page'] = $page;
        $arr['listName'] = $listName;
        $arr['model'] = $user;
        $arr['follow'] = $follow;
        $arr['btnClass'] = $btnClass;
        $arr['num'] = $result;
        $arr['isOwner'] = $isOwner;
        
        return view($view, $arr);
    }
    public function loadLikeListData($from, $to, $id){
        $userId = $id;
        $user = User::findOrFail($id);
        $values =  DB::table('t_like')->select('t_ip.id as id',"t_ip_attr.value as ip_date")
            ->join('t_ip','t_ip.id','=','t_like.resource_id')
            ->leftJoin('t_ip_attr',function($join){
                $join->on('t_ip_attr.ip_id','=','t_ip.id')
                ->whereIn('t_ip_attr.code',['10003','10006','10010']);
            })
            ->where('t_like.user_id',$userId)
            ->where('resource','ip')
            ->orderBy('t_ip_attr.value','desc')
            ->skip($from)->take($to-$from+1)->get();
        $likeList = array();
        $scoreController = new CommonScoreController;
        for($i=0;$i<count($values);$i++){
            $value = $values[$i];
            $ip = Ip::find($value->id);
            $dateStr = (is_null($value->ip_date) || $value->ip_date== '') ? '0000-00-00':$value->ip_date;
            /*由于存在星期一,九月 21,2016这种格式的日期，此处强行转换保证程序正常运行，数据修复后注销 */
            if(strpos($dateStr, '星期')===0){
                $tmp = explode(',',$dateStr);
                $tmpStr = $tmp[2];
                $tmpx = explode(' ', $tmp[1]);
                $tmpStr .=('-'.$tmpx[2]);
                $months = ['01'=>'一月', '02'=>'二月', '03'=>'三月',
                           '04'=>'四月', '05'=>'五月', '06'=>'六月',
                           '07'=>'七月', '08'=>'八月', '09'=>'九月',
                           '10'=>'十月', '11'=>'十一月', '12'=>'十二月'];
                $tmpStr .=('-'.(array_search($tmpx[1], $months)));
                $dateStr = $tmpStr;
            }
            /*转换结束*/
            $dateArr = explode('-',$dateStr);
            $year = ($dateArr[0]=='0000'?'未知':($dateArr[0]));
            $day = '';
            if($dateArr[1] == '00'){
                $day = '未知';
            }else{
                $day = $dateArr[1].'/';
                if($dateArr[2] != '00'){
                    $day .= ($dateArr[2]);
                }else{
                    $day .= '-';
                }
            }
            if(!isset($likeList[$year][$day])){
                 $likeList[$year][$day] = array();
            }
            $likeItem = array();
            $likeItem['ip'] = $ip;
            $sameListItems =LikeModel::where('resource_id', $ip->id)
                ->where('resource', 'ip')
                ->where('user_id', '!=', $id)
                ->orderBy('id', "desc")->take(3)->get();
            $likeItem['sameLikes'] = array();
            foreach($sameListItems as $sameListItem){
                array_push($likeItem['sameLikes'], $sameListItem->user);
            }

            $likeItem['ipScenes'] =IpScene::where('ip_id',$ip->id)
                ->orderBy('id','desc')->take(3)->get();
            $userReadStatus = IpUserStatus::where('user_id', $userId)
                ->where('ip_id', $ip->id)->first();
            $likeItem['readStatus'] =
                (is_null($userReadStatus) || $userReadStatus->status == "reading") ? "reading" : "readed";
            $score = $scoreController->getUserScore('ip', $ip->id, $userId);
            $likeItem['score'] =isset($score['score'])?$score['score']:0;
            $goldRecord = UserGoldRecord::where('user_id', $id)
                ->where('resource_id', $ip->id)
                ->where('type', 'income')->first();
            $likeItem['goldRecord']  = IpContributor::where('ip_id',$ip->id)->where('user_id',$id)->sum('receive_gold');

            array_push($likeList[$year][$day] , $likeItem);
        }
        return view('partview.likeitem',array('models'=>$likeList));
    }

    public function loadLikeListData_tmp($from, $to, $id)
    {
        $model     = LikeModel::where('user_id', $id)->where('resource', 'ip')->join('t_ip','t_ip.id','=','t_like.resource_id');
        $userLike  = CommonUtils::handleListDetails($model, $from, $to, true, 't_like.id');
        $userLike1 = array();
        $scoreController = new CommonScoreController;
        foreach ($userLike as $k => $v) {
            $userLike1[date('Y', strtotime($v['created_at']))][$k]               = $v;
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['samePerson'] = LikeModel::where('resource_id', $v->resource_id)->where('resource', 'ip')->where('user_id', '!=', $id)->orderBy('id', "desc")->take(4)->get();
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['ipScene']    = IpScene::where('ip_id', $v->resource_id)->where('user_id', $id)->orderBy('id', "desc")->take(3)->get();
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['goldRecord'] = UserGoldRecord::where('user_id', $id)->where('resource_id', $v->resource_id)->where('type', 'pay')->first();
            $ipUserStatus                                                        = IpUserStatus::where('user_id', $id)->where('ip_id', $v->resource_id)->first();
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['isRead']     = '正在看';
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['isReadClass'] = 'ym-icon-status';
            $scoreResult = $scoreController->getUserScore('ip', $v->resource_id);
            $userLike1[date('Y', strtotime($v['created_at']))][$k]['score']		= 	$scoreResult['score'];
            if (!empty($ipUserStatus)) {
                $userLike1[date('Y', strtotime($v['created_at']))][$k]['isRead']      = ($ipUserStatus->status == 'reading') ? '正在看' : '已看完';
                $userLike1[date('Y', strtotime($v['created_at']))][$k]['isReadClass'] = ($ipUserStatus->status == 'reading') ? 'ym-icon-status' : 'ym-icon-status-finish';
            }
        }
        return view('partview.likeitem', array('models' => $userLike1));
    }

    public function loadWorkListData($from, $to, $id)
    {
        $userProduction = $this->getProductions($id, false, false, $from, $to);


        foreach ($userProduction as $k => $v) {
            $userProduction[$k]['comment'] = Discussion::where('user_id', $id)
                ->where('resource', 'user_production')
                ->where('resource_id', $v->id)->count();
            if(!is_null(json_decode($v['intro']))){
            	// $intro = CommonUtils::explainJsonReturnFirstImageText($v['intro']);
            	// $userProduction[$k]['intro'] = $intro['intro'];
                $userProduction[$k]['intro'] = $v['intro'];
            }
        }
        $uid = Auth::check()?Auth::user()->id:0;
        return view('partview.workitem', ['models' => $userProduction,'id'=>$id,'isOwner'=>($uid == $id)]);
    }
    
    

    public function loadSalesListData($from, $to, $id)
    {
        $userProduction = $this->getProductions($id, false, true, $from, $to);
        foreach ($userProduction as $k => $v) {
            $userProduction[$k]['comment'] = Discussion::where('user_id', $id)
                ->where('resource', 'user_production')
                ->where('resource_id', $v->id)->count();
        }
        $uid = Auth::check()?Auth::user()->id:0;
        return view('partview.salesitem', ['models' => $userProduction,'id'=>$id,'isOwner'=>($uid == $id)]);
    }

    public function getProductions($userid, $delete = false, $is_sell = false, $from = 0, $to = 0)
    {
        $likeController = new CommonLikeController;
        $model          = UserProduction::where('user_id', $userid)->where('is_deleted', $delete);
        if ($is_sell) {
            $model->where('is_sell', 1);
        }
        $results = CommonUtils::handleListDetails($model, $from, $to,true, 'updated_at');
        $results = $likeController->attachLikes($this->RESOURCE_NAME_DIALOGUE, $results);
        return $results;
    }

    public function displayCreateWork($id)
    {
        $attr = SysAttrEnum::where('column', '20012')->get()->toArray();
        $ips = Ip::where('creator',Auth::user()->id)->orderBy('created_at', "desc")->take(10)->get()->toArray();
        if(empty($ips)){
        	$iplist   = DB::table('t_ip_sum')->where('code', '11003')->orderBy('value', 'desc')->select('ip_id')->take(10)->get();
    		$ipids = array();
    		foreach ($iplist as $ip) {
    			array_push($ipids, $ip->ip_id);
    		}
    		$ips = Ip::whereIn('id', $ipids)->get()->toArray();
        }
//         print_r($ips);
        $attrArr  = array('0' => '选择产品属性');
        $attrCode = array(0 => ' ');
        foreach ($attr as $k => $v) {
            array_push($attrArr, $v['name']);
            array_push($attrCode, $v['code']);
        }
		$arr = [];
		$arr['accessId'] = CommonUtils::getAliOssAccessId();
		$arr['policy'] = CommonUtils::getAliOSSPostPolicy();
		$arr['signature'] = Commonutils::getAliOSSSignature($arr['policy']);
		$arr['postUrl'] = CommonUtils::getAliUrl('post');
		$arr['showUrl'] = CommonUtils::getAliUrl('show');
		$arr['nameSeed'] = Commonutils::createRandomId('role');
		$arr['token'] = Commonutils::createUserToken(Auth::user()->id);
		$arr['id'] = $id;

        $arr['attrArr'] = $attrArr;
        $arr['attrCode'] = $attrCode;
        $arr['ips'] = $ips;

        $view = view("partview.home.workcreate", $arr);
        return $view;
    }
	public function addWorks(){
		$dom = 'img-box_work-1461655295-2auYl0.png_他姐姐诶;text-box_是顶顶顶顶顶是顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶顶大大大。;img-box_work-1461655295-2auYl1.png_;text-box_啊水水水水水水水水水水水水水水水水;link-box_百度_http://www.baidu.com;';
		$domArr = explode(';}',$dom);
		$arr = array();
		print_r($domArr);
		foreach ($domArr as $k=>$val){
			if(!empty($val)){
				$descArr = explode('_',$val);
				if($descArr[0]  == 'img-box'){
					$arr[] = array('type'=>'img','src'=>$descArr[1],'desc'=>$descArr[2]);
				}else if($descArr[0]  == 'text-box'){
					$arr[] = array('type'=>'text','text'=>$descArr[1]);
				}else if($descArr[0]  == 'link-box'){
					$arr[] = array('type'=>'link','name'=>$descArr[1],'link'=>$descArr[2]);
				}
			}
		}
		$a = json_encode($arr);
		echo $a;
	}
	
    public function addWork()
    {
        $data['id']         = Input::get('id');
        $data['title']      = CommonUtils::escapeSpecialChars(Input::get('title'));
        $data['image']      = CommonUtils::evalPics(Input::get('images_value'));
        $data['intro']      = CommonUtils::escapeSpecialChars(Input::get('intro'));
		$dom	=	Input::get('image_text_intro');
		$domArr = explode(';}',$dom);
		$introArr = array();
		foreach ($domArr as $k=>$val){
			if(!empty($val)){
				$descArr = explode('_',$val);
				if($descArr[0]  == 'img-box'){
					$introArr[] = array('type'=>'img','src'=>$descArr[1],'desc'=>$descArr[2]);
				}else if($descArr[0]  == 'text-box'){
					$introArr[]= array('type'=>'text','text'=>$descArr[1]);
				}else if($descArr[0]  == 'link-box'){
					if(stripos($descArr[2],'http://')===false && stripos($descArr[2],'https://')===false){
						$url = 'http://'.$descArr[2];
					}else{
						$url = $descArr[2];
					}
					$introArr[] = array('type'=>'link','name'=>$descArr[1],'link'=>$url);
				}
			}
		}
		$intro = json_encode($introArr);
        $prod = UserProduction::create([
	        'user_id'     => $data['id'],
	        'name'        => $data['title'],
	        'intro'       => $intro,
	        'image'       => $data['image'],
	        'is_deleted'	  => 0
        ]);
        return redirect('/user/product/'.$prod->id);
       }
	/**
	 * 作品编辑页
	 * @param unknown $id
	 * @return Ambigous <\Illuminate\View\View, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
	 */
    public function displayEditWork($id)
    {
    	$attr = SysAttrEnum::where('column', '20012')->get()->toArray();
    	$attrArr  = array('0' => '选择产品属性');
    	$attrCode = array(0 => ' ');
    	foreach ($attr as $k => $v) {
    		array_push($attrArr, $v['name']);
    		array_push($attrCode, $v['code']);
    	}
    	$userProduct = UserProduction::find($id);
    	$view = view("partview.home.editwork",
    			array('id' => $id, 'attrArr' => $attrArr,'attrCode'=>$attrCode,'userProduct'=>$userProduct ));
    	return $view;
    }
    /**
     * 保存编辑的信息
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
     */
    public function editWork(){
    	$id = Input::get('id');
    	$work = UserProduction::find($id);
    	$work->name = CommonUtils::escapeSpecialChars(Input::get('title'));
    	$work->intro = CommonUtils::escapeSpecialChars(Input::get('intro'));
    	$work->image = CommonUtils::evalPics(Input::get('image'));
    	$work->is_original = Input::get('isoriginal');
    	$work->is_sell = Input::get('issell');
    	$work->price = Input::get('price');
    	$work->attr_code = Input::get('attrcode');
    	$work->sell_intro = Input::get('sellintro');
    	$work->save();
    	return redirect('/user/product/'.$id);
    }

    private function createUserProduction($data)
    {
        return UserProduction::create([
            'user_id'     => $data['id'],
            'ip_id'       => $data['ip_id'],
            'name'        => $data['title'],
//                 'image' => $data['image'],
            'is_original' => $data['isoriginal'],
            'is_sell'     => $data['issell'],
            'price'       => $data['price'],
            'attr_code'   => $data['attrcode'],
            'intro'       => $data['intro'],
            'image'       => $data['image'],
            'sell_intro'  => $data['sellintro'],
        	'is_deleted'	  => 1
        ]);
    }

    public function searchIp()
    {
        $key = Input::get('key');
        $ip  = Ip::where('name', 'like', "%{$key}%")->get()->toArray();
        if (!empty($ip) && is_array($ip)) {
            echo CommonUtils::ajaxReturn(1, '', array('list' => $ip));
        } else {
            echo CommonUtils::ajaxReturn(-1);
        }
    }
	public function deleteWork(){
		$id = Input::get('id');
		$pro = UserProduction::find($id);
		$pro->is_deleted = 2;
		$pro->save();
		echo CommonUtils::ajaxReturn(1);
	}
}
