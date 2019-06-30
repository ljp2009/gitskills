<?php
namespace App\Common;

use App\Commands\CalculateRecommend;
use App\Models as MD;
use Auth;
use Bus;
use DB;

class RecommendAlgorithm
{
    public function __construct($user)
    {
        $this->currentUser = $user;
    }

    const REC_TYPE_PUBLIC ='public';

    public function getHallBanners()
    {
        return MD\HallBanner::orderBy('created_at', 'desc')->take(4)->get();
    }
    public function getHallActivitys()
    {
        $act = MD\Activity::where('to_date','>=', date('Y-m-d'))
            ->where('is_forbidden',0)
            ->orderBy('is_recommend','desc')
            ->orderBy('from_date')->first();
        
        if(is_null($act)){
            $act = new MD\Activity;
            $act->title = '暂无推荐活动';
            $act->text = '我们还没有可以参加的活动呦!~';
            $act->image = '';
            $act->id = 0;
        }
        return $act;

    }
    //推荐列表
    public function getHallIps($from=0, $to=3)
    {
        //$resArr = MD\UmeiiiRecommend::orderBy('created_at',"desc")->take(6)->get();
        $resArr =  MD\UmeiiiRecommendBatch::where('publish_date','<=',date('Y-m-d'))
                ->orderBy('publish_date','desc')
                ->skip($from)->take($to-$from+1)->get();
        return $resArr;
    }
    private function getPublicRecommandIp($ct=1)
    {
        return MD\HallRecommend::where('type',self::REC_TYPE_PUBLIC)->take($ct)->get();
    }
    private function getUserRecommandIp($ct=1)
    {
        if(!Auth::check()) return [];
        //获取用户最突出的属性
        //获取对应的作品
        return [];
    }
    private function getMoreLikeIp($ct=1)
    {
        $ips = DB::table('t_ip_sum')
            ->join('t_ip', 't_ip_sum.ip_id', '=', 't_ip.id')
            ->where('t_ip_sum.code', '11003')
            ->orderBy('value', 'desc')->select('t_ip_sum.ip_id')->take(1)->get();
        if(count($ips)== 0)
        {
            return [];
        }
        else
        {
            $resArr =array();
            for($i=0;$i<count($ips);$i++){
                array_push($resArr,MD\Ip::find($ips[$i]->ip_id));
            }
            return $resArr;
        }
    }
    private function getNewestIps($ct=1)
    {
        $ips = MD\Ip::orderBy('created_at')->take($ct)->get();
        return $ips;
    }

    public function getRecommendMaster(){
        $masters =  MD\UmeiiiMaster::where('user_id','<>',0)->orderBy('order')->get();
        $arr = [];
        foreach($masters as $master){
            $arr[$master->order] = $master->user;
        }
       return $arr;
    }
    public function getHallMasters($from=0, $to=0)
    {
        $userIdList = array();
        //自己follow的大神
        if (Auth::check()) {
            $currentUser = Auth::user();
            $userExpert  = DB::table('t_user_relation')
                ->join('t_user_detail_status', 't_user_relation.follow_id', '=', 't_user_detail_status.user_id')
                ->join('t_user_sum', 't_user_sum.user_id', '=', 't_user_detail_status.user_id')
                ->where('t_user_relation.user_id', '=', $currentUser->id)
                ->where('t_user_detail_status.is_expert', '=', true)
                ->where('t_user_sum.sum_code', '=', '21001') //粉丝数量
                ->orderBy('t_user_sum.value', 'desc')
                ->select('t_user_relation.follow_id')->first();
            if (!is_null($userExpert)) {
                array_push($userIdList, $userExpert->follow_id);
            }
        }
        //关注度最高的大神
        $siteExpert = MD\UserSum::where('sum_code', '=', '21001')->orderBy('value', 'desc')->take(2)->get();
        foreach ($siteExpert as $us) {
            array_push($userIdList, $us->user_id);
            if (count($userIdList)){
                break;
            }

        }
        //推荐的大神
        $recommendUsers = MD\UserDetailStatus::where('is_expert', true)->orderBy('created_at', 'desc')->take(4)->get();
        foreach ($recommendUsers as $us) {
            array_push($userIdList, $us->user_id);
            if (count($userIdList) > 3) {
                break;
            }

        }
        return MD\User::whereIn('id', $userIdList)->get();
    }
    public function getHallDimensions($from=0, $to=0)
    {
        $dims = MD\Dimension::orderBy('created_at')->take(3)->get();
        $resArr = array();
        foreach($dims as $dim)
        {
            array_push($resArr, $dim);
        }
        if(count($resArr<3)){
            $c = count($resArr);
            for($i=0;$i<(3-$c);$i++){
                $dim = new MD\Dimension;
                $dim->name = '暂无';
                array_push($resArr, $dim);
            }
        }
        return $resArr;
    }
    public function getRecommendDimension()
    {
        $dims =  MD\UmeiiiDimension::where('dimension_id','<>',0)->orderBy('order')->get();
        $arr = [];
        foreach($dims as $dim){
            $arr[$dim->order] = $dim->dimension;
        }
       return $arr;
    }
    public function getHallSpecials()
    {
        $specials = $this->getSpecialData(0,3);
       return $specials;
    }
    public function getSpecialData($from, $to){
        $resArr =  MD\Special::where('publish_date','<=',date('Y-m-d'))
                ->where('status', 1)
                ->orderBy('publish_date','desc')
                ->skip($from)->take($to-$from+1)->get();
        return $resArr;
    }
    public function getTop10($from=0, $to=0)
    {
        $flag = 3;
        $ips = DB::table('t_like_sum')
            ->join('t_ip', 't_like_sum.resource_id', '=', 't_ip.id')
            ->where('t_like_sum.resource', 'ip')
            ->where('t_like_sum.like_sum','>',0)
            ->orderBy('t_like_sum.like_sum', 'desc')->select('t_like_sum.resource_id')->take($flag)->get();
        $resArr = array();
        for($i=0; ($i<count($ips) && $i<$flag); $i++){
            array_push($resArr, MD\Ip::find($ips[$i]->resource_id));
        }
        if(count($resArr)<3)
        {
            $c = count($resArr);
            for($i=0; $i<($flag-$c); $i++){
                 $ip = new MD\Ip;
                 $ip->id = 0;
                 $ip->name = '暂无';
                 $ip->cover = '';
                array_push($resArr, $ip);
            }
        }
        return $resArr;
    }
    public function calculate()
    {
        Bus::dispatch(new CalculateRecommend($this->currentUser));
    }
    public function tagValueReCalculate($ipId, $userId){
        $ipRecTags = MD\IpRecTag::where('ip_id', $ipId)->get();
        $userRecTags = MD\UserRecTag::where('user_id', $userId)->get();
        $ipTagsArr = array();
        $userTagsArr = array();
        foreach($ipRecTags as $ipRecTag)
        {
            $ipTagsArr[$ipRecTag->code] = $ipRecTag->value;
        }
        foreach($userRecTags as $userRecTag){
            $userTagsArr[$userRecTag->code] = $userRecTag->value;
        }
        //Ip属性加权
        //User属性加权
    }

}

