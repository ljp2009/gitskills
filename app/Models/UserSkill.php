<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
  	protected $table = 't_user_skill';    
  	protected $guarded = ['id'];
  	
  	public function skill(){
  		return $this->hasOne('App\Models\SysUserSkill', 'code','code');
  	}
  	public function getSkillNameAttribute(){
      return $this->skill->name;
    }
  	public function getSkillClassAttribute(){
  		switch($this->code){
  			case '2001001':
  				return 'ym-icon-copy';
  			case '2001002':
  				return 'ym-icon-screenwriter';
  			case '2001003':
  				return 'ym-icon-dubb';
  			case '2001004':
  				return 'ym-icon-painter';
  			case '2001005':
  				return 'ym-icon-design';
  			case '2001006':
  				return 'ym-icon-music';
  			case '2001007':
  				return 'ym-icon-move';
  			case '2001008':
  				return 'ym-icon-model';
  			case '2001009':
  				return 'ym-icon-photography';
  			case '2001010':
  				return 'ym-icon-makeup';
  			case '2001011':
  				return 'ym-icon-development';
  			case '2001012':
  				return 'ym-icon-plan';
  			case '2001013':
  				return 'ym-icon-pm';
  			case '2001014':
  				return 'ym-icon-data';
  		}
  	}
  	public function getLevelNameAttribute(){
  		switch ($this->level){
  			case 1:
  				return 'green';
  			case 2:
  				return 'blue';
  			case 3:
  				return 'yellow';
  			case 4:
  				return 'orange';
  			case 5:
  				return 'red';
  		}
  	}
}
