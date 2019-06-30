<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_like', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('resource');
            $table->integer('resource_id');
            $table->integer('like_sum_id');
            $table->timestamps();
        });
    }

    /*t_ip*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_like');
    }
}
