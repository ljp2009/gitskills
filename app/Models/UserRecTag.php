<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRecTag extends Model
{
    // 用户推荐标签
    protected $table = 'sys_rec_tag';
    protected $guarded   = ['id'];
}
