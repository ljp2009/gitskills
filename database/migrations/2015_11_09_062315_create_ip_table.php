<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', array('cartoon','story','game','light'));//1：动漫，2：小说，3：游戏
            $table->text('cover');
            $table->integer('creator');
            $table->integer('like_sum')->default(0);
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
        Schema::drop('t_ip');
    }
}
