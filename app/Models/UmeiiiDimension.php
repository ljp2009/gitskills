<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmeiiiDimension extends Model
{
    protected $table     = 't_umeiii_dimension';
    protected $guarded   = ['id'];
    public function dimension(){
    	return $this->hasOne('App\Models\Dimension','id','dimension_id');
    }
}
