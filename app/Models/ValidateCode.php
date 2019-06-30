<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidateCode extends Model
{
    const TYPE_MOBILE = 0;
    const TYPE_EMAIL = 1;
    const STATUS_NEW = 0;
    const STATUS_VALIDATE = 1;
    const FUNC_REGIST = 0;
    const FUNC_RESET = 1;
    protected $table     = 't_validate_code';
    protected $guarded   = ['id'];
}
