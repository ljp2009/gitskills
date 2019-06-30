<?php

namespace App\Models;

use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Common\Text;
use Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\IpAttr;
use App\Models\SysAttr;
use App\Models\LikeModel;
use App\Models\LikeSumModel;
use App\Models\ScoreModel;
use App\Models\ScoreSumModel;
class Ip extends Model
{
    const IPTYPE_CARTOON = 'cartoon';
    const IPTYPE_STORY   = 'story';
    const IPTYPE_GAME    = 'game';
    const IPTYPE_LIGHT    = 'light';
    protected $table     = 't_ip';
    protected $guarded   = ['id'];

    public function user() {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    //贡献数量
    public function getContributorsCountAttribute() {
        return $this->getIpSumData('11001');
    }
    //推荐数量
    public function getRecommendsCountAttribute() {
        return $this->getIpSumData('11002');
    }
    private function getIpSumData($code) {
        $obj = IpSum::where('ip_id', $this->id)->where('code', '=', $code)->first();
        return is_null($obj) ? 0 : $obj->value;
    }
    // 评分
    public function grade() {
        return $this->hasOne('App\Models\ScoreSumModel', 'resource_id','id')->where('resource','ip');
    }
    public function averageGrade(){
    	$grade = $this->grade;
    	if($grade){
    		return ceil($grade->score_sum/$grade->score_count);
    	}
    	return 0;
    }
    public function getAverageScoreAttribute(){
    	$score = ScoreSumModel::where('resource_id', $this->id)->first();
    	if($score) return ceil($score->score_sum/$score->score_count);
    	return 0;
    }
    public function getUserScoreAttribute(){
        if(!Auth::check())return 0;
        $score = ScoreModel::where('resource_id', $this->id)
                ->where('user_id', Auth::user()->id)->first();
        if($score) return $score->score;
        return 0;
    }
    public function getIsLikeAttribute(){
        if(!Auth::check())return false;
        return LikeModel::CheckLike('ip', $this->id);
    }
    public function getLikeCountAttribute(){
        $likeSum = LikeSumModel::where('resource', 'ip')
            ->where('resource_id', $this->id)
            ->first();
        if(is_null($likeSum)) return 0;
        return $likeSum->like_sum;
    }
    //属性
    public function attrs() {
        return $this->hasMany('App\Models\IpAttr', 'ip_id');
    }
    public function getTimeAttrAttribute(){
        return $this->getAttrDisplay(['10003','10006','10010']);
    }
    public function getNumberAttrAttribute(){
        return $this->getAttrDisplay(['10004','10011']);
    }
    public function getStatusAttrAttribute(){
        return $this->getAttrDisplay(['10002','10007','10009']);
    }
    public function getAuthorAttrAttribute(){
        return $this->getAttrDisplay(['10012','10013','10005','10008']);
    }
    private function getAttrDisplay($codes){
        $attrs = IpAttr::whereIn('code',$codes)
            ->where('ip_id',$this->id)->get();
        if(count($attrs) == 0){
            return '';
        }else{
            $res = '';
            foreach($attrs as $attr){
                if(trim($attr->display)!=''){
                    if($res != ''){
                        $res .= ' | ';
                    }
                    $res .= trim($attr->display);
                }
            }
            return $res;
        }
    }
    //标签
    //public function tags(){
    //    return $this->hasMany('App\Models\IpTag');
    //}
    public function getTagsAttribute($value){
        $arr = explode(';', $value);
        $res = [];
        foreach($arr as $a){
            if(!empty($a)){
                array_push($res, $a);
            }
        }
        return $res;
    }
    public function setTagsAttribute($value){
        $arr = implode(';', $value);
        $this->attributes['tags'] = $arr.';';
    }
    //封面
    public function getCoverAttribute($value) {
    	return Image::makeImage($value);
    }
    public function getImageAttribute(){
        return $this->cover;
    }
    //类别标签
    public function getIpTypeLabelAttribute() {
        $str = '';
        switch ($this->type) {
            case self::IPTYPE_GAME:
                $str = '游 戏';
                break;
            case self::IPTYPE_CARTOON:
                $str = '动 漫';
                break;
            case self::IPTYPE_STORY:
                $str = '小 说';
                break;
            case self::IPTYPE_LIGHT:
                $str = '轻小说';
                break;
        }
        return $str;
    }
    public function getTypeLabelAttribute(){
        return $this->ipTypeLabel;
    }
    //访问地址
    public function getIpPathAttribute(){
        if($this->id == 0) return '#';
        return '/ip/'.$this->id;
    }
    public function getDetailUrlAttribute(){
        if($this->id == 0) return '#';
        return '/ip/'.$this->id;
    }
    //简介
    public function intro(){
        return $this->hasOne('App\Models\IpIntro', 'ip_id', 'id');
    }
    //
    public function getCardInfoAttribute()
    {
        return $this->ipTypeLabel.'&nbsp;|&nbsp;'.$this->authorAttr.'&nbsp;|&nbsp;'. $this->statusAttr;
    }

}
