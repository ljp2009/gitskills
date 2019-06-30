<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Common\Image;
use Auth;

class Dimension extends Model
{
    protected $table = 't_dimension';
    protected $guarded = ['id'];

    public function getHeaderAttribute($value) {
       return Image::makeImage($value);
    }
    public function getCoverAttribute($value) {
        return $this->header;
    }
    public function user() {
    	return $this->hasOne('App\Models\User','id','user_id');
    }
    public function getIsEnterAttribute(){
        if($this->authority == 1){
            return  'activity';
        }
        if(!Auth::check()){return '';}
        $userId = Auth::user()->id;
        if($userId == $this->user_id){return 'owner';}
        $dimensionEnter = DimensionEnter::where('dimension_id',$this->id)
            ->where('user_id', $userId)->first();
        if(empty($dimensionEnter)){return 'N';}
        return $dimensionEnter->is_enter;
    }
    public function enterSum() {
    	return $this->hasOne('App\Models\DimensionSum','dimension_id','id')->where('code','31001');
    }
    public function getEnterSumValueAttribute() {
        if(is_null($this->enterSum)){
            return 0;
        }else{
            return $this->enterSum->value;
        }
    }
    public function publishSumModel() {
    	return $this->hasOne('App\Models\DimensionSum','dimension_id','id')->where('code','31002');
    }
    public function getPublishSumValueAttribute(){
        if(is_null($this->publishSumModel) ){
            return 0;
        }else{
            return $this->publishSumModel->value;
        }
    }
    public function getLinkHtmlAttribute(){
    	$link = json_decode($this->link,true);
    	if(isset($link['link']) && isset($link['show'])){
    		return "<a href='".$link['link']."' target='__blank'>".$link['show']."</a>";
    	}
    	return null;
    }
    public function getLinkAttribute($value){
    	$link = json_decode($value,true);
    	if(isset($link['link']) && isset($link['show'])){
    		return $link;
    	}
    	return ;
    }
    public function getDimensionPathAttribute(){
        if($this->id == 0) return '#';
        return '/dimpub/list/diminfo/0/'.$this->id;
    }
    public function getUrlAttribute(){
        if($this->id == 0) return '#';
        return '/dimpub/list/diminfo/0/'.$this->id;
    }
    public function getShotTitle($length){
    	$str_cut = $this->name;
    	if(mb_strlen($str_cut) > $length){
  			$str_cut = mb_substr($str_cut,0,$length)."..";
  		}else{
  			$str_cut = $str_cut;
  		}
  		return $str_cut;
    	return $str_cut;
    }
    public function getTagNamesAttribute(){
        $tags = DimensionAttr::where('dimension_id', $this->id)->get();
        $tagStr = '';
        foreach($tags as $tag){
           $tagStr .= (';'.$tag->value);
        }
        return $tagStr;
    }
    public function getTagCodesAttribute(){
        $tags = DimensionAttr::where('dimension_id', $this->id)->get();
        $tagStr = '';
        foreach($tags as $tag){
           $tagStr .= (';'.$tag->code);
        }
        return $tagStr;
    }
}
