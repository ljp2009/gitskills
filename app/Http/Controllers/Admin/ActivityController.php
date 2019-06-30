<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Common\Image;
use Input;
use Auth;
use App\Models\ActivityPartner;
use Carbon;
use App\Common\CommonUtils;

class ActivityController extends Controller {

    public function getList() {
        $items = Activity::paginate(15);
        return view('admins.pages.activitylist', ['items' => $items]);
    }

    public function postDelete() {
        $item = Activity::find(Input::get('id'));
        if (!is_null($item)) {
            $item->delete();
        }
        return response()->json(['res' => true]);
    }

    public function getAdd() {
        $item = new Activity;
        return view('admins.pages.activityform', ['model' => $item, 'uploadParams' => $this->getUploadParams()]);
    }

    public function getModify($id) {
        $item = Activity::find($id);
        return view('admins.pages.activityform', ['model' => $item, 'uploadParams' => $this->getUploadParams()]);
    }

    public function postSubmit() {
        $id = Input::get('id');
        if (empty($id) || $id == 0) {
            $item = new Activity;
        } else {
            $item = Activity::find($id);
        }
        $item->title = Input::get('title');
        $item->text = Input::get('text');
        $item->from_date = Input::get('from_date');
        $item->user_id = Auth::user()->id;
        $item->is_offline = Input::get('is_offline') != '0';
        $item->is_forbidden = 0;
        if (!empty(str_replace(';', '', Input::get('image')))) {
            $item->image = Input::get('image');
        }
        if (!empty(Input::get('to_date'))) {
            $item->to_date = Input::get('to_date');
        } else {
            $item->to_date = null;
        }
        if (!empty(Input::get('address'))) {
            $item->address = Input::get('address');
        } else {
            $item->address = '';
        }
        if (!empty(Input::get('scale'))) {
            $item->scale = Input::get('scale');
        } else {
            $item->scale = 0;
        }
        if (!empty(Input::get('linkText'))) {
            $item->join_link = Input::get('linkText');
        } else {
            $item->join_link = '';
        }
        $item->type = strstr($item->join_link, 'dimpub')?12:11;

        $item->save();
        return redirect('admin/act/list');
    }

    public function postRecommend() {
        $type = Input::get('type');
        $id = Input::get('id');
        if ($type == 'true') {
            Activity::where('is_recommend', true)->update(['is_recommend' => false]);
            Activity::where('id', $id)->update(['is_recommend' => true]);
        } else {
            Activity::where('id', $id)->update(['is_recommend' => false]);
        }
        return response()->json(['res' => true]);
    }

    private function getUploadParams() {
        $uploadParams = Image::getUploadAliImageParams();
        return $uploadParams;
    }

    //活动切换是否使用
    public function postActIsForbidden() {
        $data = Input::get();
        $return = Activity::where('id', $data['act_id'])->update(['is_forbidden' => $data['status'] == 1 ? 0 : 1]);
        return response()->json(['res' => $return]);
    }

    //参与作品切换是否使用
    public function postJoinIsForbidden() {
        $data = Input::get();
        $return = ActivityPartner::where('id', $data['join_id'])->update(['is_forbidden' => $data['status'] == 1 ? 0 : 1]);
        return response()->json(['res' => $return]);
    }

    //活动参与活动的作品列表act-joins-list
    public function getActJoinsList($act_id) {
        $act = Activity::find($act_id);
        Carbon::setlocale('zh');
        $now_data = date('Y-m-d H:i:s');
        if ($act->type == 11) {
            $acts_obj = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                    ->leftjoin('t_user_production', 't_activity_partner.resource_id', '=', 't_user_production.id')
                    ->select('t_activity_partner.is_forbidden as is_forbidden_par', 't_activity.*', 't_activity_partner.*',
                            't_user_production.like_sum as like_sum', 't_user_production.name','t_user_production.intro'
                            ,'t_user_production.image')
                    ->where('t_activity_partner.act_id', $act_id);
//                    ->where('t_activity.from_date', '<=', $now_data);
            $acts_obj = $this->get_obj($acts_obj,11);
//            ->where('t_activity.to_date', '>=', $now_data)
            $acts = $acts_obj->orderby('t_activity_partner.created_at', 'desc')
//                    ->get()->toarray();
                    ->paginate(15);
        } else if ($act->type == 12) {
            $acts_obj = ActivityPartner::leftjoin('t_activity', 't_activity_partner.act_id', '=', 't_activity.id')
                    ->leftjoin('t_dimension_publish', 't_activity_partner.resource_id', '=', 't_dimension_publish.id')
                    ->select('t_activity_partner.is_forbidden as is_forbidden_par', 't_activity.*', 't_activity_partner.*',
                            't_dimension_publish.like_sum as like_sum','t_dimension_publish.image');
            $acts_obj = $this->get_obj($acts_obj,12);
            $acts = $acts_obj->where('t_activity_partner.act_id', $act_id)
                    ->where('t_activity.from_date', '<=', $now_data)
                    ->where('t_activity.to_date', '>=', $now_data)
                    ->orderby('t_activity_partner.created_at')
//                    ->get()->toarray();
                    ->paginate(15);
        }
//        Pe($acts);
        foreach ($acts as $key => $val) {
            $acts[$key]['is_like'] = $val->getIsLikeAttribute();
            $acts[$key]['avatar'] = $val->user->avatar->getPath(2);
            $acts[$key]['display_name'] = $val->user->display_name;
            $acts[$key]['homeurl'] = $val->user->homeUrl;
            $acts[$key]['detailUrl'] = $val->ProDim->detailUrl;
            $images = trim($val['image'], ';') ? explode(';', trim($val['image'], ';')) : [];
            if (empty($images)) {
                $acts[$key]['image'] = Image::makeImage(';')->getpath(1);
                continue;
            }
            $acts[$key]['image'] = Image::makeImage($images[0])->getpath();
        }
        return view('admins.pages.activityjoinslist', ['items' => $acts, 'model' => $act]);
    }

    private function get_obj($obj,$type) {
        $get_data = Input::get();
//        Pe($get_data);
        $dis_part = isset($get_data['dis_part'])?$get_data['dis_part']:'';
        if ($dis_part == 'all') {
        } elseif ($dis_part == 'unforbidden') {
            $obj =  $obj->where('t_activity_partner.is_forbidden', 1);
        } elseif ($dis_part == 'forbidden') {
            $obj = $obj->where('t_activity_partner.is_forbidden', 0);
        }
        $set_sort = isset($get_data['set_sort'])?$get_data['set_sort']:'';
        if($set_sort == 'create_at'){
            $obj = $obj->orderby('t_activity_partner.create_at','desc');
        }elseif($set_sort == 'like_sum'){
            if($type == 11){
                $obj = $obj->orderby('t_user_production.like_sum','desc');
            }else{
                $obj = $obj->orderby('t_dimension_publish.like_sum','desc');
            }
        }
        return $obj;
    }
    
    public function getActEditResult($act_id){
        $item = Activity::find($act_id);
        return view('admins.pages.activityresult', ['model' => $item, 'uploadParams' => $this->getUploadParams()]);
    }
    public function postActResult(){
        $data = Input::get();
        Activity::where('id',$data['id'])->update(['result'=>$data['result']]);
        return redirect('admin/act/list');
    }
    
    public function postSendInfo(){
        $data = Input::get();
        $res = CommonUtils::createPrivateLetter($data['user_id'], 0, $data['description'],'');
        return response()->json(['res' => true]);
    }

}
