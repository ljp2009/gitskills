<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;

///周边
class IpPeripheral extends Model
{
    protected $table = 't_ip_peripheral';
  	protected $guarded = ['id'];
  	public function scopePopular($query)
  	{
  		return $query;
  	}
  	public function getImageAttribute($value)
    {
        return CU::getImagePath('peripheral','image',$value);
    }
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
    public function images()
    {
    	return array($this->image); 
    }
    public function getDetailUrlAttribute($value)
    {
      return '/peripheral/'.$this->id;
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
