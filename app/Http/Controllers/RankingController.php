<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ip;
use App\Models\UserProduction;
use App\Models\UserSum;
use App\Models\User;
use App\Http\Controllers\Common\CommonLikeController as likeCtrl;
use App\Common\CommonUtils;

class RankingController extends Controller
{
    /*以下为榜单的方法*/
    public function getRankingList(){
        return view('rankinglist');
    }
    public function loadRankingPartview($partName){ 
        if(!$this->checkRankingPartName($partName))return '无效内容';
        $fun = 'load' . studly_case($partName).'Ranking';
        return $this->$fun();
    }
    public function loadRankingPage($partName, $page){
        $title ='更多'.$this->getRankingName($partName).'排行榜';
        if($partName == 'user'){
            $title = '达人榜';
        }
        return view('rankingmorelist', ['title'=>$title, 'listName'=>$partName, 'page'=>$page]);
    }
    public function loadRankingData($partName, $from, $to){
        $fun = 'get' . studly_case($partName).'RankingData';
        $items = $this->$fun($from, $to);
        $fun = 'load'.studly_case($partName).'PageItem';
        return $this->$fun($items);
    }
    private function loadIpPageItem($items){
        $arr = [];
        foreach($items as $item){
            $obj = $item['obj'];
            array_push($arr, [
                'image'=>$obj->cover,
                'type'=>$obj->typeLabel,
                'name'=>$obj->name,
                'intro'=>$obj->intro,
                'url'=>$obj->detailUrl
            ]);
        }
        return view('partview.searchresitem', ['models'=>$arr]);
    }
    public function loadProdPageItem($items){
        $arr = [];
        foreach($items as $item){
            $obj = $item['obj'];
            array_push($arr, [
                'image'=>$obj->cover,
                'type'=>$obj->typeLabel,
                'name'=>$obj->name,
                'intro'=>$obj->intro,
                'url'=>$obj->detailUrl
            ]);
        }
        return view('partview.searchresitem', ['models'=>$arr]);
    }
    public function loadDiscPageItem($items){
        return view('partview.ip.discussion', ['items'=>$items]);
    }
    public function loadUserPageItem($items){
        return view('partview.useritem' ,['models'=>$items]);
    }
    //载入子页面
    private function loadIpRanking(){
        $items = $this->getIpRankingData(0,2);
        return view('partview.ranking.ipranking',['items'=>$items]);
    }
    private function loadProdRanking(){
        $items = $this->getProdRankingData(0,2);
        return view('partview.ranking.ipranking',['items'=>$items]);
    }
    private function loadDiscRanking(){
        $items = $this->getDiscRankingData(0,2);
        return view('partview.ip.discussion',['items'=>$items]);
    }
    //获取数据
    private function getIpRankingData($from, $to){
         $ips = Ip::orderBy('like_sum', 'desc')->skip($from)->take($to-$from+1)->get();
         return $this->convertObjToItems($ips, 'ip');
    }
    private function getProdRankingData($from, $to){
         $prods = UserProduction::orderBy('like_sum', 'desc')->skip($from)->take($to-$from+1)->get();
         return $this->convertObjToItems($prods, 'prod');
    }
    private function getDiscRankingData($from, $to){
         $prods = UserProduction::where('relate_type', 'disc')->orderBy('like_sum', 'desc')->skip($from)->take($to-$from+1)->get();
         return $this->convertObjToItems($prods, 'prod');
    }
    private function convertObjToItems($objs, $type){
         $items = [];
         foreach($objs as $obj){
             $items[$obj->id] = [];
             $items[$obj->id]['obj'] = $obj;
             $items[$obj->id]['type'] = $type;
         }
         return $items;
    }
    //用户
    private function getUserRankingData($from, $to){
        $sqls = UserSum::where('sum_code', '21002');
        $items =   CommonUtils::handleListDetails($sqls, $from, $to , true,'value');
        $arr = [];
        
        foreach($items as $item){
            array_push($arr, $item->user);
        }

        return $arr;
    }
    //获取页面
    private function checkRankingPartName($partName){
        return in_array($partName, ['ip','prod','disc']);
    }
    private function getRankingName($partName){
        $map = ['ip'=>'IP', 'prod'=>'作品', 'disc'=>'长评'];
        if(!in_array($partName, $map)) return '';
        return $map[$partName];
    }
}
