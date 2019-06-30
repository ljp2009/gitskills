<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13FuwenModel extends Model
{
    protected $table = 'game13_fuwen'; 
    protected $fillable = ['fuwenset_id', 'ti', 'su', 'gong',
    	'fang', 'ji', 'ind'];
}
