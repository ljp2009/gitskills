<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13UserModel extends Model
{
    protected $table = 'game13_user'; 
    protected $fillable = ['user_id', 'headimg', 'hero1', 'hero2', 
    	'hero3', 'fuwen1', 'fuwen2', 'fuwen3'];

    public function gameUserInfo(){
    	return $this->hasOne('App\Models\UserGameInfoModel', 'user_id', 'user_id');
    }
}
