<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\Image;
class Special extends Model
{
    protected $table     = 't_special';
    protected $guarded   = ['id'];

    public function getImgAttribute($value){
        return Image::makeImage($value);
    }
    public function getCoverAttribute(){
        return $this->img;
    }
    public function items(){
        return $this->hasMany('App\Models\SpecialItem','Special_id','id');
    }
}
