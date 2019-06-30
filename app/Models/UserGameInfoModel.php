<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGameInfoModel extends Model
{
    protected $table = 't_user_game_info'; 
    protected $fillable = ['user_id', 'level', 'recent_win_times', 'game_id', 'status'];

    public function playingGame(){
    	return $this->hasOne('App\Models\Game13OnprocessModel', 'id', 'game_id');
    }


}
