<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13OnprocessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_onprocess', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user1');
			$table->integer('user2');
			$table->integer('round');
			$table->integer('stage');
			$table->integer('nextround');
			$table->integer('nextstage');
			$table->integer('timechecker');
			$table->integer('busy');
			$table->string('mode'); //test/normal

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
        Schema::drop('game13_onprocess');
    }
}
