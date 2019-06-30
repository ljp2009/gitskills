<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\Image;
class UmeiiiMaster extends Model
{
    protected $table     = 't_umeiii_master';
    protected $guarded   = ['id'];
    public function user(){
		return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
    public function getImgAttribute($value){
  		return Image::makeImage($value);
    }
}
