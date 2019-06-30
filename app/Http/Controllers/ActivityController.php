<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityPartner;
use App\Common\Image;
use App\Models\Discussion;
use Carbon, Auth, DB;

class ActivityController extends Controller {

    /**********************新接口**********************/
    //活动列表
    public function getActivityList($page) {
        return view('activitylist', ['title' => '活动中心', 'type' => 'activity',
            'listName' => 'default','page' => $page]);
    }
    public function getActivityListData($from,$to) {
        $now_data  = date('Y-m-d H:i:s');
        $data_list = Activity::where('is_forbidden',false)
            ->orderby('to_date','desc')
            ->orderby('from_date')
            ->skip($from)
            ->take($to-$from+1)->get();
        foreach ($data_list as $key => $one) {
            $data_list[$key]['leave_days'] = floor((strtotime($one['to_date']) - time()) / (3600 * 24));
            $data_list[$key]['count']      = ActivityPartner::where('act_id', $one->id)->count();
        }
        return view('activitys.listactivity',['data_list' => $data_list]);
    }

    //参与者列表
    public function getJoinPartnerList($page, $pid){
        return $this->getPartnerList('join', $page, $pid);
    }
    public function getJoinPartnerListData($from, $to, $pid){
        $act = Activity::findOrFail($pid);
        $resource = 't_'.$act->resource;
        $partners = ActivityPartner::where('act_id', $pid) 
            ->join($resource, $resource.'.id', '=', 't_activity_partner.resource_id')
            ->whereNull($resource.'.deleted_at')
            ->orderBy('t_activity_partner.created_at', 'desc')
            ->skip($from)->take($to-$from+1);
        $sql = '';
        $partners = $partners->get();
        return view('activitys.partneritem', ['models'=>$partners, 'sql'=>$sql, 'isFinish'=>$act->isFinish]);
    }

    //作品排行列表
    public function getRankPartnerList($page, $pid){
        return $this->getPartnerList('rank', $page, $pid);
    }
    public function getRankPartnerListData($from, $to, $pid){
        $act = Activity::findOrFail($pid);
        $orderField = $act->isFinish?'t_activity_partner.score':($resource.'.like_sum');
        $resource = 't_'.$act->resource;
        $partners = ActivityPartner::where('t_activity_partner.act_id', '=', $pid) 
            ->join($resource, $resource.'.id', '=', 't_activity_partner.resource_id')
            ->whereNull($resource.'.deleted_at')
            ->orderBy($orderField, 'desc')
            ->skip($from)->take($to-$from+1)->get();
        return view('activitys.partneritem', ['models'=>$partners, 'isFinish'=>$act->isFinish]);
    }

    private function getPartnerList($listName, $page, $pid){
        $act = Activity::findOrFail($pid);
        return view('activitys.detailpage', [
            'listName'=>$listName,
            'page'=>$page,
            'pid' =>$pid,
            'model' =>$act
        ]);
    }

    //
    /**********************旧接口**********************/
    public function getList() {
        return view('activitylist', ['title' => '活动中心', 'resource' => 'ip_scene', 'type' => 'act',
            'listName' => 'get_list_data', 'id' => 1, 'page' => 0]);
    }

    public function get_list_data($from, $to,$page) {
        $now_data = date('Y-m-d H:i:s');
        $data_list = Activity::where('is_forbidden',false);
        $data_list= $data_list->orderby('to_date','desc')->orderby('from_date')->skip($from)->take($to-$from+1)->get();
        foreach ($data_list as $key => $one) {
            $data_list[$key]['leave_days'] = floor((strtotime($one['to_date']) - time()) / (3600 * 24));
            $data_list[$key]['count'] = ActivityPartner::where('act_id', $one->id)->count();
        }
        return view('activitys.listactivity',['data_list' => $data_list]);
    }

    public function getShowJoin($id) {
        Carbon::setlocale('zh');
        $act = Activity::find($id);
        $act->count_num = ActivityPartner::where('act_id', $id)->count();
        $act->days = floor((strtotime($act->to_date) - time()) / (3600 * 24));
        $now_data = date('Y-m-d H:i:s');
        $joinLink = ($act->join_link.'?act_id='.$act->id);
        if(!Auth::check()){
            $idStr    = sprintf("%08d", $act->id);
            $joinLink = '/auth/login/A00005000'.$idStr;
        }
        return view('activity', [
            'model'    => $act,
            'joinLink' => $joinLink,
            'type'     => 'act',
            'listName' => 'get_join_list_data',
            'id'       => 1,
            'page'     => 0]);
    }
    public function getShowRank($id) {
        Carbon::setlocale('zh');
        $act = Activity::find($id);
        $act->count_num = ActivityPartner::where('act_id', $id)->count();
        $act->days = floor((strtotime($act->to_date) - time()) / (3600 * 24));
        $now_data = date('Y-m-d H:i:s');
        $joinLink = ($act->join_link.'?act_id='.$act->id);
        if(!Auth::check()){
            $idStr    = sprintf("%08d", $act->id);
            $joinLink = '/auth/login/A00005000'.$idStr;
        }
        return view('activity', [
            'model'    => $act,
            'joinLink' => $joinLink,
            'type'     => 'act',
            'listName' => 'get_ranking_list_data',
            'id'       => 1,
            'page'     => 0]);
    }
    
    public function get_ranking_list_data($from,$to,$act_id){
        $act = Activity::find($act_id);
        Carbon::setlocale('zh');
        $now_data = date('Y-m-d H:i:s');
        if ($act->type == 11) {
            $acts_ranking = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                        ->leftjoin('t_user_production', 't_activity_partner.resource_id', '=', 't_user_production.id')
                        ->select('t_activity.*', 't_activity_partner.*','t_user_production.image',
                            't_user_production.like_sum as like_sum', 't_user_production.name','t_user_production.intro',
                            't_user_production.id as publish_idd')
                        ->where('t_activity_partner.act_id', $act_id)
                        ->where('t_activity_partner.is_forbidden', 0)
                        ->orderby('t_user_production.like_sum', 'desc')
                        ->skip($from)->take($to-$from+1)->get();
        } else if ($act->type == 12) {
            $acts_ranking = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                        ->leftjoin('t_dimension_publish', 't_activity_partner.resource_id', '=', 't_dimension_publish.id')
                        ->where('t_activity_partner.act_id', $act_id)
                        ->select( 't_activity.*', 't_activity_partner.*','t_dimension_publish.image',
                            't_dimension_publish.like_sum as like_sum','t_dimension_publish.text',
                            't_dimension_publish.id as publish_idd')
                        ->where('t_activity_partner.is_forbidden', 0)
                        ->orderby('t_dimension_publish.like_sum','desc')
                        ->skip($from)->take($to-$from+1)->get();
        }
        if ($act->type == 11) {
        foreach ($acts_ranking as $key => $val) {
            $acts_ranking[$key]['is_like'] = $val->getIsLikeAttribute();
            $acts_ranking[$key]['avatar'] = $val->user->avatar->getPath(2);
            $acts_ranking[$key]['display_name'] = $val->user->display_name;
            $acts_ranking[$key]['homeurl'] = $val->user->homeUrl;
            if(!isset($val->ProDim)){
                unset($acts_ranking[$key]);
                continue;
            }
            $acts_ranking[$key]['detailUrl'] = $val->ProDim->detailUrl;
            $acts_ranking[$key]['discussion'] = Discussion::countDiscuss('user_production', $val->publish_idd);
            $images = trim($val['image'], ';') ? explode(';', trim($val['image'], ';')) : [];
            if (empty($images)) {
                $acts_ranking[$key]['image'] = '';
                continue;
            }
            $acts_ranking[$key]['image'] = Image::makeImage($images[0])->getpath(1);
        }
        }elseif ($act->type == 12) {
            foreach ($acts_ranking as $key => $val) {
                $acts_ranking[$key]['is_like'] = $val->getIsLikeAttribute();
                $acts_ranking[$key]['avatar'] = $val->user->avatar->getPath(2);
                $acts_ranking[$key]['display_name'] = $val->user->display_name;
                $acts_ranking[$key]['homeurl'] = $val->user->homeUrl;
                if(!isset($val->ProDim)){
                    unset($acts_ranking[$key]);
                    continue;
                }
                $acts_ranking[$key]['detailUrl'] = $val->ProDim->detailUrl;
                $acts_ranking[$key]['discussion'] = Discussion::countDiscuss('dimension_publish', $val->publish_idd);
                $images = trim($val['image'], ';') ? explode(';', trim($val['image'], ';')) : [];
                if (empty($images)) {
                    $acts_ranking[$key]['image'] = '';
                    continue;
                }
                $acts_ranking[$key]['image'] = Image::makeImage($images[0])->getpath(1);
            }
        }
        return view('activitys.detailactivityranking', ['model'=>$act, 'acts_ranking' => $acts_ranking]);
    }
    public function get_join_list_data($from,$to,$act_id){
        $act = Activity::find($act_id);
        Carbon::setlocale('zh');
        $now_data = date('Y-m-d H:i:s');
        if ($act->type == 11) {
            $join_acts = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                        ->leftjoin('t_user_production', 't_activity_partner.resource_id', '=', 't_user_production.id')
                        ->select('t_activity.*', 't_activity_partner.*','t_user_production.image',
                            't_user_production.like_sum as like_sum', 't_user_production.name','t_user_production.intro',
                                't_user_production.id as publish_idd')
                        ->where('t_activity_partner.act_id', $act_id)
                        ->where('t_activity_partner.is_forbidden', 0)
                        ->orderby('t_activity_partner.created_at', 'desc')
                        ->skip($from)->take($to-$from+1)->get();
        } else if ($act->type == 12) {
            $join_acts = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                        ->leftjoin('t_dimension_publish', 't_activity_partner.resource_id', '=', 't_dimension_publish.id')
                        ->select( 't_activity.*', 't_activity_partner.*','t_dimension_publish.image',
                            't_dimension_publish.like_sum as like_sum','t_dimension_publish.text',
                                't_dimension_publish.id as publish_idd')
                        ->where('t_activity_partner.act_id', $act_id)
                        ->where('t_activity_partner.is_forbidden', 0)
                        ->orderby('t_dimension_publish.created_at', 'desc')
                        ->skip($from)->take($to-$from+1)->get();
        }
        if ($act->type == 11) {
            foreach ($join_acts as $key => $val) {
                $join_acts[$key]['is_like'] = $val->getIsLikeAttribute();
                $join_acts[$key]['avatar'] = $val->user->avatar->getPath(2);
                $join_acts[$key]['display_name'] = $val->user->display_name;
                $join_acts[$key]['homeurl'] = $val->user->homeUrl;
                if(!isset($val->ProDim)){
                    unset($join_acts[$key]);
                    continue;
                }
                $join_acts[$key]['detailUrl'] = $val->ProDim->detailUrl;
                $join_acts[$key]['discussion'] = Discussion::countDiscuss('user_production', $val->publish_idd);
                $images = trim($val->image, ';') ? explode(';', trim($val['image'], ';')) : [];
                if (empty($images)) {
                    $join_acts[$key]['image'] = '';
                    continue;
                }
                $join_acts[$key]['image'] = Image::makeImage($images[0])->getpath(1, '100w_20h_1e_1c');
        }
        } else if ($act->type == 12) {
            foreach ($join_acts as $key => $val) {
                $join_acts[$key]['is_like'] = $val->getIsLikeAttribute();
                $join_acts[$key]['avatar'] = $val->user->avatar->getPath(2);
                $join_acts[$key]['display_name'] = $val->user->display_name;
                $join_acts[$key]['homeurl'] = $val->user->homeUrl;
                if(!isset($val->ProDim)){
                    unset($join_acts[$key]);
                    continue;
                }
                $join_acts[$key]['detailUrl'] = $val->ProDim->detailUrl;
                $join_acts[$key]['discussion'] = Discussion::countDiscuss('dimension_publish', $val->publish_idd);
                $images = trim($val->image, ';') ? explode(';', trim($val['image'], ';')) : [];
                if (empty($images)) {
                    $join_acts[$key]['image'] = '';
                    continue;
                }
                $join_acts[$key]['image'] = Image::makeImage($images[0])->getpath(1, '100w_20h_1e_1c');
            }
        }
        var_dump(count($join_acts));
        return view('activitys.detailactivityjoin', ['model' => $act, 'join_acts' => $join_acts]);
    }
    

}
