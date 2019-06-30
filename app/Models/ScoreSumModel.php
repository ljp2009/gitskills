<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreSumModel extends Model
{
    protected $table = "t_score_sum";

    protected $fillable = ['resource', 'resource_id'];
}

?>
