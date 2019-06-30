<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevelModel extends Model
{
    protected $table = 't_user_level'; 
    protected $fillable = array('user_id', 'level');
}
