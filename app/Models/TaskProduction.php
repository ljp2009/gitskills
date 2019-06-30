<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskProduction extends Model
{
    protected $table = 't_task_porduction'; 
	protected $guarded = ['id'];
    public function getImageAttribute($value){
        return CU::getImagePath('taskProduction','image',$value);
    }
}
