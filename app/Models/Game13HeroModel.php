<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13HeroModel extends Model
{
    protected $table = 'game13_hero'; 
    protected $fillable = ['user_id', 'heroname', 'level', 'heroimg'];
}
