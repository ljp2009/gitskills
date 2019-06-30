<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SysUserSkill extends Model
{
    protected $table   = 'sys_user_skill';
    protected $guarded = ['id'];
    public function __tostring(){
        if($this->name == '') return '(未设置)';
        return $this->name;
    }
}
