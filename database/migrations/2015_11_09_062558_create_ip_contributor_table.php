<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpContributorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_contributor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ip_id');
            $table->integer('user_id');
            $table->enum('type', array('info','intro','role','scene','dialogue','tag','attr'));
            $table->integer('obj_id');
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
        Schema::drop('t_ip_contributor');
    }
}
