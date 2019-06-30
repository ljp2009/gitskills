<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameUserHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_user_history', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('game_id');
			$table->integer('user_id');
			$table->integer('win');
			$table->integer('enemy_user_id');
			$table->string('game_type');

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
        Schema::drop('game_user_history');
    }
}
