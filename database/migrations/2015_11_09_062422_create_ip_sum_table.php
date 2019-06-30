<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_sum', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('ip_id');
             $table->integer('value');
             $table->string('code');
            $table->timestamps();
             
             //$table->integer('contributor_count');
             //$table->integer('recommend_count');
             //$table->integer('expert_like_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_ip_sum');
    }
}
