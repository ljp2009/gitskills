<?php

namespace App\Models;

use App\Common\CommonUtils as CU;
use Illuminate\Database\Eloquent\Model;

class UserGuaranteeDeal extends Model
{
    const GUARANTEE_IN    = 1; //担保中
    const TASKSTEP_SUCCESS  = 2; //交易成功
    const TASKSTEP_FALE     = 3; //交易失败

    protected $table       = 't_user_guarantee_deal';
    protected $guarded     = ['id'];
    
	
}
