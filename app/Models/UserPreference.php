<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    protected $table = 't_user_preference';
    
  	protected $guarded = ['id'];
}
