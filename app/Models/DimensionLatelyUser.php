<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimensionLatelyUser extends Model
{
    protected $table = 't_dimension_lately_user';
    protected $guarded = ['id'];
    
    public function user()
    {
    	return $this->hasOne('App\Models\User','id','user_id');
    }
}
	