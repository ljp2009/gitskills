<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSignIn extends Model
{
    protected $table     = 't_user_sign_in';
    protected $guarded   = ['id'];
}
