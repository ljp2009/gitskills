<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGameInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_game_info', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('recent_win_times');
			$table->integer('level');
			$table->integer('game_id');
			$table->string('status'); //0=NA 1=Waiting 2=Playing

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
        Schema::drop('t_user_game_info');
    }
}
