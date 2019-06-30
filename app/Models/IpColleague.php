<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;

///同人
class IpColleague extends Model
{
    protected $table = 't_ip_colleague';
  	protected $guarded = ['id'];
  	public function scopePopular($query)
  	{
  		return $query;
  	}
  	public function getCoverAttribute($value)
    {
      	return CU::getImagePath('colleague','cover',$value);
    }
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
    public function images()
    {
    	return array($this->cover);
    }
    public function getDetailUrlAttribute($value)
    {
      return '/colleague/'.$this->id;
    }
    public function getLinkAttribute($value){
    	$link = json_decode($value,true);
    	if(isset($link['link']) && isset($link['show'])){
    		return $link;
    	}
    	return ;
    }
    public function getLinkHtmlAttribute(){
    	$link = $this->link;
    	if(isset($link['link']) && isset($link['show'])){
    		return "<a href='".$link['link']."' target='__blank'>".$link['show']."</a>";
    	}
    	return ;
    }
	public function getShotTitle($length){
    	$str_cut = $this->title;
    	if(mb_strlen($str_cut) > $length){
  			$str_cut = mb_substr($str_cut,0,$length)."..";
  		}else{
  			$str_cut = $str_cut;
  		}
  		return $str_cut;
    	return $str_cut;
    }
}
