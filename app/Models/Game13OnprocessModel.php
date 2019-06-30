<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13OnprocessModel extends Model
{
    protected $table = 'game13_onprocess'; 
    protected $fillable = array('user1', 'user2', 'timechecker', 'round', 'stage', 'nextround', 'nextstage');

    public function gameUser1(){
    	return $this->hasOne('App\Models\Game13UserOnprocessModel', 'id', 'user1');
    }

    public function gameUser2(){
    	return $this->hasOne('App\Models\Game13UserOnprocessModel', 'id', 'user2');
    }

    public function gameUser1OnlineStatus(){
    	return $this->hasOne('App\Models\Game13OnlineStatusModel', 'game_user', 'user1');
    }

    public function gameUser2OnlineStatus(){
    	return $this->hasOne('App\Models\Game13OnlineStatusModel', 'game_user', 'user2');
    }

    public function gameUser1Data(){
    	return $this->hasOne('App\Models\Game13OnprocessDataModel', 'game_user', 'user1');
    }

    public function gameUser2Data(){
    	return $this->hasOne('App\Models\Game13OnprocessDataModel', 'game_user', 'user2');
    }

}
