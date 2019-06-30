<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ProductionPublishController as ProdCtrl;
use App\Http\Controllers\Common\CommonScoreController;
use App\Models\Ip;
use App\Models\IpRole;
use App\Models\IpIntro;
use App\Models\IpScene;
use App\Models\IpColleague;
use App\Models\IpPeripheral;
use App\Models\Dimension;
use App\Models\ListItem;
use App\Models\User;
use App\Models\UserProduction;
use App\Models\LikeModel;
use App\Common\CommonUtils;
use Auth, Redirect, Input;
class IpRelatedController extends Controller
{
    /* * *
     * 获取IP相关的列表
     * * */
    public function loadRelatedList($related, $page, $ipid){
        if($related == 'dim'){//相关次元不使用通用的列表
            return view('dimensionlist', ['id'=>$ipid, 'page'=>$page, 'name'=>'相关次元']);
        }
        $title = $this->getListTitle($related);
        return view('detaillist', [
            'title'=>$title,
            'type'=>'ip-'.$related,
            'listName'=>'v',
            'id'=>$ipid,
            'page'=>$page]);
    }
    private function getListTitle($relatedName){
        switch($relatedName){
            case 'disc':
                return '更多评论';
            case 'coll':
                return '更多同人';
            case 'peri':
                return '更多周边';
        }
        return '';
    }

    /* * *
     * 获取IP相关列表的数据
     * * */
    public function loadRelatedListData($related, $from, $to, $ipid){
        $func = camel_case('get_'.$related.'_list_data');
        $models = $this->$func($ipid, $from, $to);
		return view('partview.detaillistitem', array('models'=>$models));
    }
    private function getDiscListData($ipid, $from, $to){
        $userProds = UserProduction::where('ip_id', $ipid)
            ->where('relate_type','disc')
            ->orderBy('like_sum','desc')
            ->skip($from)->take($to-$from+1)->get();
        return ListItem::makeUserProductionListItems($userProds);
    }
    private function getCollListData($ipid, $from, $to){
        $userProds = UserProduction::where('ip_id', $ipid)
            ->where('relate_type','coll')
            ->orderBy('like_sum','desc')
            ->skip($from)->take($to-$from+1)->get();
        return ListItem::makeUserProductionListItems($userProds);
    }
    private function getPeriListData($ipid, $from, $to){
        $userProds = UserProduction::where('ip_id', $ipid)
            ->where('relate_type','peri')
            ->orderBy('like_sum','desc')
            ->skip($from)->take($to-$from+1)->get();
        return ListItem::makeUserProductionListItems($userProds);
    }
    /* * *
     * 创建IP相关内容
     * * */
    public function createRelated($related, $ipid){
        $prodCtrl = new ProdCtrl;
        switch($related){
            case 'disc':
                return $prodCtrl->getCreateDiscussion($ipid);
            case 'coll':
                return $prodCtrl->getCreateColl($ipid);
            case 'peri':
                return $prodCtrl->getCreatePeri($ipid);
        }
        return $prodCtrl->getCreate();
    }
    public function showEditRelated($related, $id){
        $prodCtrl = new ProdCtrl;
        switch($related){
            case 'disc':
                return $prodCtrl->getModifyDiscussion($id);
            case 'coll':
                return $prodCtrl->getModifyColl($id);
            case 'peri':
                return $prodCtrl->getModifyPeri($id);
        }
        return $prodCtrl->getModify($id);
    }
    public function deleteRelated($related){
        $prodCtrl = new ProdCtrl;
        switch($related){
            case 'disc':
            return $prodCtrl->postDeleteAjax();
            case 'coll':
            return $prodCtrl->postDeleteAjax();
            case 'peri':
            return $prodCtrl->postDeleteAjax();
        }
        return response()->json(['res'=>false,'info'=>'unknown']);
    }
}
