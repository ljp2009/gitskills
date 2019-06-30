<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Common\Image;
class SysIpSurvey extends Model
{
    protected $table = 'sys_ip_survey';
    
  	protected $guarded = ['id'];
  	
  	public function getImagePathAttribute()
  	{
  		// return CU::getImagePath('IpSurvey','image',$this->image);
  		return Image::makeImage($this->image);
  	}
}
