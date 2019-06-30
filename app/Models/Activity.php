<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class Activity extends Model {

    protected $table = 't_activity';
    protected $guarded = ['id'];

    public function getImageAttribute($value) {
        return Image::makeImages($value);
    }

    public function getCoverAttribute() {
        if (count($this->image) > 0) {
            return $this->image[0];
        }
        else {
            return Image::makeImage('');
        }
    }

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function partners(){
        return $this->hasMany('App\Models\ActivityPartner', 'act_id', 'id');
    }
    public function getPartnerNumAttribute(){
        return $this->partners->count();
    }
    public function getDaysAttribute() {
        $toDay = strtotime($this->to_date);
        $nowDay = strtotime(date('Y-m-d'));
        $leaveDays = ($toDay - $nowDay)/(3600 * 24);
        return (int)$leaveDays;
    }
    public function getResourceAttribute(){
        if($this->type == 11){
            return 'user_production';
        }
        else if($this->type == 12){
            return 'dimension_publish';
        }
        else{
            return '';
        }
    }
    public function getJoinLinkAttribute($value){
        $joinLink = '';
        if(Auth::check()){
            $joinLink = $value.'?act_id='.$this->id;
        }else{
            $idStr    = sprintf("%08d", $this->id);
            $joinLink = '/auth/login/A00005000'.$idStr;
        }
        return $joinLink;
    }
    public function getDetailUrlAttribute(){
        if($this->isFinish){
            return '/activity/list/rank/0/'.$this->id;
        }else{
            return '/activity/list/join/0/'.$this->id;
        }
        
    }
    public function getIsFinishAttribute(){
        return $this->to_date < date('Y-m-d H:i:s');
    }
    static public function is_act($resource_id,$resource) {
        $data = Input::all();
        $res = false;
        if (isset($data['act_id']) && $data['act_id'] > 0) {
            $user_info = auth::user();
            $data['user_id'] = $user_info->id;
            $data['resource'] = $resource;
            $data['resource_id'] = $resource_id;
            $data['score'] = 0;
            $data['is_forbidden'] = 0;
            $res = ActivityPartner::create(get_table_data(new ActivityPartner, $data));
        }
        if($res){
            return $data['act_id'];
        }
        return false;
    }
}
