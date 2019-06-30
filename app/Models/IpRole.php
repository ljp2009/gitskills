<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Common\CommonUtils as CU;
use App\Common\Image;
class IpRole extends Model
{
    protected $table = 't_ip_role';
  	protected $guarded = ['id'];
    use SoftDeletes;

    public function getHeaderAttribute($value)
    {
        return Image::makeImage($value);
    }
    public function getImageAttribute($value)
    {
        return Image::makeImage($value);
    }
    public function getIpInfo()
    {
    	return $this->hasOne('App\Models\Ip','id','ip_id');
    }
    public function ip()
    {
    	return $this->hasOne('App\Models\Ip','id','ip_id');
    }
    public function user(){
    	return $this->hasOne('App\Models\User', 'id', 'creator');
    }
    public function getDetailUrlAttribute()
    {
    	return '/roles/'.$this->id;
    }
}
