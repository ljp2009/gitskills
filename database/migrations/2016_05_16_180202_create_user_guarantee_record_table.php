<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGuaranteeRecordTable extends Migration
{
    /**
     * Run the migrations.
     * 担保操作记录
     * @return void
     */
    public function up()
    {
    	Schema::create('t_user_guarantee_record', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('user_id');
    		$table->integer('guarantee_id');//担保ID
    		$table->integer('gold_id');//关联金币交易id
    		$table->tinyInteger('action');//操作行为(1:担保，2:支付，3:退还)
    		$table->string('remark');//操作备注
    		$table->timestamps();
    	});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_user_guarantee_record');
    }
}
