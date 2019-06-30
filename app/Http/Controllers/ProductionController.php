<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use app\http\controllers\Controller;
use Like;
use App\Models\UserProduction;
class ProductionController extends Controller
{
    //user production
    public function getUserProdList($uid, $page){
        //return view('iprelatedprodlist', ['title'=>$title, 'ipid'=>$ipid]);
    }
    public function getUserProdListData($uid, $from, $to){
    }
    //recommend production list
    //iprelated production list
    public function getRecColl($ipid){
    }
    public function getRecPeri($ipid){
    }
    public function getRecDiscussion($ipid){
    }
    public function getColleagueList($order, $page, $ipid){
        $view = $this->makeIpRelatedListPageView('同人作品',$ipid);
        return $view->with('page',$page)
                    ->with('pagetype','coll')
                    ->with('order',$order);
    }
    public function getPeripheralList($order, $page, $ipid){
        $view = $this->makeIpRelatedListPageView('周边产品',$ipid);
        return $view->with('page',$page)
                    ->with('pagetype','peri')
                    ->with('order',$order);
    }
    public function getDiscussionList($order, $page, $ipid){
        $view = $this->makeIpRelatedListPageView('长评论',$ipid);
        return $view->with('page',$page)
                    ->with('pagetype','disc')
                    ->with('order',$order);
    }
    public function getIpRelatedListData($type, $order, $from, $to, $ipid){
        $idArr = [];
        if($order == 'like'){
            $idArr = Like::getLikeOrderList('user_production',
                 ['ip_id'=>$ipid,'relate_type'=>$type], $from, $to);
            $models = $this->getProductions($idArr);
        }
        elseif($order == 'time'){
            $prods = $this->getTimeOrderProds($type, $from, $to, $ipid);
            $models = $this->getProdSums($prods);
        }
        return view('partview.prod.prodlistdata',['models'=>$models]);
    }

    private function makeIpRelatedListPageView($title, $ipid){
        return view('iprelatedprodlist', ['title'=>$title, 'id'=>$ipid]);
    }
    /**
     * 获取根据时间排序的列表
     * */
    private function getTimeOrderProds($type, $from, $to, $ipid){
        $prods =UserProduction::where('relate_type', $type)
            ->where('ip_id',$ipid)
            ->orderBy('created_at', 'desc')
            ->skip($from)->take($to-$from+1)
            ->get();
        return $prods;
    }
    /**
     * 已知产品列表，获取各自的like数
     * */
    private function getProdSums($prods){
        $ids = [];
        $res = [];
        $idSort = [];
        for($i=0;$i<count($prods);$i++){
            array_push($ids, $prods[$i]->id);
            $res[$i] =['id'=>$prods[$i]->id, 'sum'=>0, 'obj'=> $prods[$i]];
        }
        $sumArr = Like::getResourceLikeSum('user_production', $ids);
        foreach($res as $sort=>$arr){
            if(array_key_exists($arr['id'], $sumArr)){
                $res[$sort]['sum'] = $sumArr[$arr['id']];
            }
        }
        return $res;
    }
    /**
     * 已知sum数获取产品对象
     * */
    private function getProductions($idSums){
        $idArr = [];
        $idSort = [];
        $res = [];
        foreach($idSums as $sort=>$idSum){
            array_push($idArr, $idSum['id']);
            $idSort[$idSum['id']] = $sort;
            $res[$sort] = $idSum;
        }
        $objs = UserProduction::whereIn('id', $idArr)->get();
        foreach($objs as $obj){
            $res[$idSort[$obj->id]]['obj'] = $obj;
        }
        return $res;
    }

    private function getSingleProduction($id){
    }
}
