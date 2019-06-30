<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13UserOnprocessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_user_onprocess', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->integer('luck');
			$table->integer('luckpoint');
			$table->integer('hero1');
			$table->integer('hero2');
			$table->integer('hero3');
			$table->integer('stage');
			$table->integer('round');
			$table->string('headimg');
			$table->string('reservedcards')->nullable();
			$table->integer('fuwen1');
			$table->integer('fuwen2');
			$table->integer('fuwen3');
			$table->integer('init_feature_add');
			$table->integer('is_waiting');
			$table->string('topcards')->nullable();
			$table->string('middlecards')->nullable();
			$table->string('bottomcards')->nullable();
			$table->string('extracards')->nullable();
			$table->integer('use_luckpoint');

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
        Schema::drop('game13_user_onprocess');
    }
}
