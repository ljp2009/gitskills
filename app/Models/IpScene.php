<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Common\CommonUtils as CU;
use App\Common\Image;
use App\Models\Verifiedable;
class IpScene extends Model
{
    protected $table = 't_ip_scene';
    protected $guarded = ['id'];
    use SoftDeletes;
    use Verifiedable;

    public function getImageAttribute($value)
    {
        return Image::makeImages($value);
    }
    public function getFirstImageAttribute()
    { //就得首图属性，以后首图都统一为cover
        return $this->cover;
    }
    public function getCoverAttribute(){
        if(count($this->image)>0){
            return $this->image[0];
        }else{
            return Image::makeImage('');
        }
    }
    public function user(){
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function ip(){
        return $this->hasOne('App\Models\Ip', 'id', 'ip_id');
    }
    public function getDetailUrlAttribute()
    {
        return '/ipscene/'.$this->id;
    }

}
