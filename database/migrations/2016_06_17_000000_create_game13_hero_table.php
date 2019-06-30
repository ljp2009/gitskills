<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13HeroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_hero', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->string('heroname');
			$table->integer('level');
			$table->integer('qinmi')->default(0); //percentage
			$table->integer('totalinvokedtimes');
			$table->string('heroimg')->nullable();

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
        Schema::drop('game13_hero');
    }
}
