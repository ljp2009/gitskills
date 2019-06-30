<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LongDiscussion extends Model
{
    protected $table = 't_long_discussion';
  	protected $guarded = ['id'];
  	
  	public function user()
  	{
  		return $this->hasOne('App\Models\User', 'id', 'user_id');
  	}
  	public function getLinkUrlAttribute(){
  		return '/common/list/discussiondetail/0/'.$this->id;
  	}
  	
  	public function getShotTitle($length){
  		$str_cut = $this->text;
  		$str_cut = mb_substr($str_cut,0,$length)."..";
  		return $str_cut;
  	}
  	
  	public function getEditUrlAttribute(){
  		return '/common/discuss/editlong/'.$this->id;
  	}
}
