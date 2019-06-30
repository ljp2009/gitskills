<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGuaranteeDealTable extends Migration
{
    /**
     * Run the migrations.
     * 担保交易
     * @return void
     */
    public function up()
    {
    	Schema::create('t_user_guarantee_deal', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('user_id');
    		$table->decimal('initial_gold', 9, 2);//初始担保金币数
    		$table->decimal('remain_gold', 9, 2);//剩余担保金币数
    		$table->string('resource');//关联类型
    		$table->integer('resource_id');//关联id
    		$table->tinyInteger('status')->default(1);//转态 ：1：担保中；2：交易成功；3：交易失败
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
        Schema::drop('t_user_guarantee_deal');
    }
}
