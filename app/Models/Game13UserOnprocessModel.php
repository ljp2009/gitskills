<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13UserOnprocessModel extends Model
{
    protected $table = 'game13_user_onprocess'; 
    protected $fillable = array('user_id', 'luck', 'luckpoint', 'hero1', 'hero2', 'hero3', 
            'fuwen1', 'fuwen2', 'fuwen3', 'init_feature_add', 
            'stage', 'headimg', 'round', 'is_waiting');

    public function gameUserInfo(){
    	return $this->hasOne('App\Models\UserGameInfoModel', 'user_id', 'user_id');
    }

    public function gameHero1(){
    	return $this->hasOne('App\Models\Game13HeroOnprocessModel', 'id', 'hero1');
    }

    public function gameHero2(){
    	return $this->hasOne('App\Models\Game13HeroOnprocessModel', 'id', 'hero2');
    }

    public function gameHero3(){
    	return $this->hasOne('App\Models\Game13HeroOnprocessModel', 'id', 'hero3');
    }

    private function evalCards($cardStr){
    	$cardarr = explode(',', $cardStr);
    	$re = [];
    	foreach($cardarr as $onec){
    		array_push($re, intval($onec));
    	}
    	return $re;
    }

    public function top(){
    	return $this->evalCards($this->topcards);
    }

    public function middle(){
    	return $this->evalCards($this->middlecards);
    }

    public function bottom(){
    	return $this->evalCards($this->bottomcards);
    }

    public function extra(){
        return $this->evalCards($this->extracards);
    }

    public function reserved(){
    	return $this->evalCards($this->reservedcards);
    }

    public function userData(){
        return $this->hasOne('App\Models\Game13OnprocessDataModel', 'game_user', 'id');
    }
}
