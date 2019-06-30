<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
use App\Models\SysAttrEnum;
use App\Common\Image;
class IpRoleSkill extends Model
{
    protected $table = 't_ip_role_skill';
  	protected $guarded = ['id'];
  	public function getHeaderAttribute($value)
  	{
        return Image::makeImage($value);
  	}
  	public function getImageAttribute($value)
  	{
        return Image::makeImage($value);
  	}
    public function getSkillTypeNameAttribute(){
        $attrInfo = SysAttrEnum::where('code', $this->skill_type)->first();
        if(is_null($attrInfo)){
            return '';
        }
        return $attrInfo->name;
    }
  	public function role(){
  		return $this->hasOne('App\Models\IpRole','id','role_id');
  	}
}
