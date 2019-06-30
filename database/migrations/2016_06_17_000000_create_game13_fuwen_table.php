<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13FuwenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_fuwen', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('fuwenset_id');
			$table->integer('ti');
			$table->integer('su');
			$table->integer('gong');
			$table->integer('fang');
			$table->integer('ji');
			$table->string('ind'); //1/2/3

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
        Schema::drop('game13_fuwen');
    }
}
