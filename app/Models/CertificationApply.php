<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\SysUserSkill;
use App\Models\SysAttrEnum;
/**
 * 被邀请人记录model
 * @author admin
 *
 */
class CertificationApply extends Model
{
    protected $table = 't_certification_apply';

  	protected $guarded = ['id'];
	//技能名称
  	public function getSkillNameAttribute(){
  		$value = SysUserSkill::where('code', $this->skill)->value('name');
  		return $value;

  	}
  	//技能等级
  	public function getSkillLevelNameAttribute(){
  		$value = SysAttrEnum::where('code', $this->skill_level)->value('name');
  		return $value;
  	}
  	//创建时间
  	public function getCreatedDateAttribute()
  	{
  		return date('Y-m-d', strtotime($this->created_at));
  	}
  	//审核状态
  	public function getCertifiResultAttribute(){
  		//审核完成
  		if($this->status == 2){
  			if($this->result == 1){
  				return "通过";
  			} else {
  				return "未通过";
  			}
  		//待审核
  		} else if($this->status == 0){
  			return "待审核";
  		//审核中
  		} else if($this->status == 1){
  			return "审核中";
  		}
  	}

}
