<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreModel extends Model
{
	protected $table = 't_score';

	 public function user(){
		return $this->hasOne('App\User', 'id', 'user_id');
	 }
}
