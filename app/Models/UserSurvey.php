<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSurvey extends Model
{
    protected $table = 't_user_survey';
    
  	protected $guarded = ['id'];
}
