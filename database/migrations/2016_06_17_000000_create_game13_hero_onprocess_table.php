<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13HeroOnprocessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_hero_onprocess', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('heroid');
			$table->string('heroname');
			$table->integer('level');
			$table->integer('oriblood');
			$table->integer('blood');
			$table->integer('ti');
			$table->integer('su');
			$table->integer('gong');
			$table->integer('fang');
			$table->integer('ji');
			$table->string('pic');
			$table->string('negativeeff')->nullable();
			$table->string('positiveeff')->nullable();
			$table->string('bisha_skill')->nullable();
			$table->string('dazhao_pic')->nullable();

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
        Schema::drop('game13_hero_onprocess');
    }
}
