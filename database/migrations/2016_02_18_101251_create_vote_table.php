<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_vote', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('task_id');
    		$table->integer('delivery_id');
    		$table->integer('user_id');
    		$table->boolean('is_like');//Y：喜欢，N：不喜欢
    		$table->tinyInteger('step');//1:第一轮  2:第二轮
    		$table->tinyInteger('status');//1:投票中  2:结束
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
        Schema::drop('t_vote');
    }
}
