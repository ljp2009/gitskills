<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_sum', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('user_id');
             $table->string('sum_code');
             $table->integer('value');
            $table->timestamps();
             //$table->integer('follow_count_id');
             //$table->string('fans_count_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_user_sum');
    }
}
