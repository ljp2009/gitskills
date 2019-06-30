<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityPartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_activity_partner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('act_id')->nullable();
            $table->enum('resource',['user_production','dimension_publish'])->nullable();
            $table->integer('resource_id')->nullable();
            $table->integer('score')->nullable();
            $table->boolean('is_forbidden')->nullable();
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
        Schema::drop('t_activity_partner');
    }
}
