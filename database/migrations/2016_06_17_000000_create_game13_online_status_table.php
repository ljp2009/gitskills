<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13OnlineStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_online_status', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('game_id');
			$table->integer('game_user');
			$table->integer('health_timestamp');
			$table->integer('online');

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
        Schema::drop('game13_online_status');
    }
}
