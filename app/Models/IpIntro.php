<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpIntro extends Model
{
    protected $table = 't_ip_intro';
  	protected $guarded = ['id'];

    public function __tostring(){
        return mb_substr($this->intro, 0, 50, 'utf-8');
    }
    public function getShotIntro($length){
        return mb_substr($this->intro, 0, $length, 'utf-8');
    }
}
