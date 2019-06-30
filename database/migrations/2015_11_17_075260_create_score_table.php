<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_score', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource');
            $table->integer('resource_id');
            $table->integer('score');
            $table->integer('score_sum_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('is_sys');
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
        Schema::drop('t_score');
    }
}
