<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13HeroOnprocessModel extends Model
{
    protected $table = 'game13_hero_onprocess'; 
    protected $fillable = array('heroid', 'heroname', 'level', 'oriblood','blood', 'ti', 'su', 'gong', 'fang', 'ji', 'luck', 'pic');
}
