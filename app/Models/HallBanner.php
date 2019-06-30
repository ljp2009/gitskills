<?php

namespace App\Models;

use App\Common\CommonUtils as CU;
use App\Common\Image;

class HallBanner extends HallObjModel
{
    protected $table   = 't_hall_banner';
    protected $guarded = ['id'];
    public function getImagePathAttribute()
    {
    	return Image::makeImage($this->image);
//         return CU::getImagePath('reshall', 'banner', $this->image);
    }
    public function getImageAttribute($value){
        return Image::makeImage($value);
    }
}
