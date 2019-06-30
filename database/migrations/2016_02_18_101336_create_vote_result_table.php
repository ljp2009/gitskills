<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_vote_result', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('task_id');
    		$table->integer('delivery_id');
    		$table->integer('like_count');//喜欢的数量
    		$table->integer('dislike_count');//不喜欢的数量
    		$table->float('ratio');//比例
//     		$table->tinyInteger('step');//1:第一轮  2:第二轮
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
        Schema::drop('t_vote_result');
    }
}
