<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::dropIfExists('user_credits');
        Schema::create('t_user_credit', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');//用户id
            $table->integer('level');//信誉等级
            $table->integer('score');//信誉得分
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
        Schema::drop('t_user_credit');
    }
}
