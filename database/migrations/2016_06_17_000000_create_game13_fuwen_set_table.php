<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGame13FuwenSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game13_fuwen_set', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id');
			$table->string('fuwenset_name');
			$table->integer('is_default');

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
        Schema::drop('game13_fuwen_set');
    }
}
