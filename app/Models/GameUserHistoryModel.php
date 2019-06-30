<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameUserHistoryModel extends Model
{
    protected $table = 'game_user_history'; 
    protected $fillable = ['game_id', 'user_id', 'win', 'enemy_user_id', 'game_type'];
}
