<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game13OnlineStatusModel extends Model
{
    protected $table = 'game13_online_status'; 
    protected $fillable = ['game_id', 'game_user', 'health_timestamp', 'online'];
}
