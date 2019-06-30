<?php

namespace App\Http\Controllers;

use App;
use App\Http\Controllers\Controller;
use App\Models\Ip;
use App\Models\UserProduction;
use App\Models\HallBanner;
use App\Models\UmeiiiRecommend;
use App\Models\UmeiiiRecommendBatch as Batch;
use App\Models\SpecialItem;
use App\Models\Special;
use App\Models\UserDetailStatus;
use App\Common\CommonUtils as cu;
use App\Common\VoteHandler;
use DB;
use App\Http\Controllers\Common\CommonLikeController as likeCtrl;
use Auth,Redirect,Input;

class ResHallController extends Controller
{
    public function index()
    {
        $list = App::make('algorithm')->getHallBanners();
        $arr = [];
        $arr['name'] = '资源大厅';
        $arr['list'] = $list;
        if(Auth::check()) {
           $userStatus = Auth::user()->getDetailStatus;
           if($userStatus != null && $userStatus->first_login){
                $arr['first_login'] = true;
                $userStatus->first_login = false;
                $userStatus->save();
           }
        }
        return view('reshall', $arr);
    }

    public function loadPartview($partview)
    {
        $fun = 'load' . studly_case($partview);
        //echo $fun;
        return $this->$fun();
        //return view('partview.reshall.'.$partview);
    }

    public function loadBanners()
    {
        return view('partview.reshall.banner',
            array('models' => App::make('algorithm')->getHallBanners()));
    }
    public function loadActivitys()
    {
        return view('partview.reshall.activity',
            array('model' => App::make('algorithm')->getHallActivitys()));
    }
    public function loadMasters()
    {
        return view('partview.reshall.master',
            array('models' => App::make('algorithm')->getRecommendMaster()));
    }
    public function loadRecommends()
    {
        $recommendBatchs= App::make('algorithm')->getHallIps(0, 0);
        //return view('partview.reshall.recommend', array('models' => ));
        if(count($recommendBatchs) > 0){
           $model = $recommendBatchs[0];
        }else{
            $model = null;
        }
        return view('partview.reshall.recommend', array('model' =>$model));
    }
    public function loadDimensions()
    {
        return view('partview.reshall.dimension',
            array('models' => App::make('algorithm')->getRecommendDimension()));
    }
    public function loadSpecials()
    {
        return view('partview.reshall.special',
            array('models' => App::make('algorithm')->getHallSpecials()));
    }
    public function loadTop10()
    {
        return view('partview.reshall.top10',
            array('models' => App::make('algorithm')->getTop10()));
    }
    public function postVote(){
        $voteId = Input::get('voteId');
        $userId = Auth::id();
        $value = Input::get('value');
        $res = VoteHandler::updateUserVoteRecord($voteId, $userId, $value);
        return response()->json($res);
    }
    public function search($key, $page)
    {
        return view('searchlist', array('type' => 'search', 'page' => $page, 'listName' => $key));
    }
    public function postSearch(){
        $keyWord = Input::get('keyword');
        return $this->search($keyWord, 0);
    }
    public function searchData($key, $from, $to)
    {
        $objs = Ip::where('name', 'like', '%' . $key . '%')->skip($from)
            ->take($to - $from + 1)->get();
        return view('partview.searchresitem', array('models' => self::getSearchRes($objs)));
    }
    private static function getSearchRes($valueArr, $type = 'ip')
    {
        $res = array();
        foreach ($valueArr as $value) {
            switch ($type) {
                case 'ip':
                    array_push($res, [
                        'image' => $value->cover,
                        'name'  => $value->name,
                        'type'  => $value->ipTypeLabel,
                        'intro' => $value->intro,
                        'url'   => '/ip/' . $value->id,
                        'tags'  => array($value->ipTypeLabel),
                    ]);
                    break;
                case 'spec':
                    array_push($res, [
                        'image' => $value->img,
                        'name'  => $value->name,
                        'type'  => $value->tag,
                        'intro' => $value->intro,
                        'url'   => $value->url,
                        'tags'  => array($value->tag),
                    ]);
                    break;
                default:
                    array_push($res, [
                        'image' => $value->cover,
                        'name'  => $value->name,
                        'type'  => $value->type,
                        'url'   => '/ip/' . $value->id,
                        'tags'  => array($value->ipTypeLabel),
                    ]);
                    break;
            }
        }
        return $res;
    }
    public function getRecommendMoreList($page){
        return $this->getRecommendList($page);
    }
    public function getRecommendMoreListData($from, $to){
        return $this->getRecommendListData($from, $to);
    }
    public function getRecommendList($page){
    	return view('reshalllist',['page'=>$page,'type'=>'reshall']);
    }
    public function getRecommendListData($from,$to){
        $recommendBatchs= App::make('algorithm')->getHallIps($from,$to);
    	return view('partview.reshall.recommendlistitem', array('models' => $recommendBatchs));
    }
    /**
     * 显示创建资源大厅推荐banner视图
     * @return Ambigous <\Illuminate\View\View, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
     */
    public function showCreateHallBanner(){
    	return view('partview.reshall.showcreatehallbanner');
    }

	/**
	 * 创建推荐内容
	 * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse, mixed, \Illuminate\Foundation\Application, \Illuminate\Container\static>
	 */
    public function createHallBanner(){
    	$url = trim(Input::get('url'));
    	$description = cu::escapeSpecialChars(Input::get('description'));
    	$image = Input::get('image');
    	HallBanner::create(['url'=>$url,'description'=>$description,'image'=>$image]);
    	return redirect('/reshall');
    }
    public function getSpecialItem($id, $page=0){
        $special = Special::find($id);
        return view('specialitemlist', ['special'=>$special, 'page'=>$page]);
    }
    public function getSpecialItemData($id, $from, $to){
        $models = SpecialItem::where('special_id', $id)
                ->skip($from)->take($to-$from+1)->get();
        return view('partview.searchresitem', array('models' => self::getSearchRes($models, 'spec')));
    }




    public function getSpecialList($page){
    	return view('speciallist',['page'=>$page,'type'=>'special']);
    }

    public function getSpecialListData($from, $to){
        $items= App::make('algorithm')->getSpecialData($from,$to);
    	return view('partview.speciallistitem',['models'=>$items,'head'=>'hidden']);
    }
}
