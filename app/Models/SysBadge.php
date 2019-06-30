<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Common\CommonUtils as CU;
class SysBadge extends Model
{
    protected $table = 'sys_badge';
    
    protected $guarded = ['id'];
    
    public function getBadgeAttribute($value)
    {
    	return CU::getImagePath('user','badge',$value);
    }
}
