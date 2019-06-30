<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DimensionEnter extends Model
{
    protected $table = 't_dimension_enter';
    protected $guarded = ['id'];
    

    public function dimension(){
		return $this->hasOne('App\Models\Dimension', 'id', 'dimension_id');
	}

}
