<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGoldRecord extends Model
{
	protected $table = 't_user_gold_record';
	
	protected $guarded = ['id'];
	
	const PAY_GOLD_SUCCESS  = 1; //支出交易成功
	const PAY_GOLD_FALE     = 2; //支出交易失败
	const INCOME_GOLD_SUCCESS  = 3; //收入交易成功
	const INCOME_GOLD_FALE     = 4; //收入交易失败
}
