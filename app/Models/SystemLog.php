<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $table     = 'sys_log';
    protected $guarded   = ['id'];
}
