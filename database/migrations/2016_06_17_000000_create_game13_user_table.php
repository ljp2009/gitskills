<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13UserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_user', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->string('headimg')->nullable();
			$table->integer('iswin');
			$table->integer('hero1');
			$table->integer('fuwen1');
			$table->integer('hero2');
			$table->integer('fuwen2');
			$table->integer('hero3');
			$table->integer('fuwen3');

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
        Schema::drop('game13_user');
    }
}
