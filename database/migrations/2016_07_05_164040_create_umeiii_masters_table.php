<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUmeiiiMastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_umeiii_master', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('creator');
            $table->integer('order')->nullable();
            $table->text('intro')->nullable();
            $table->string('name')->nullable();
            $table->string('img')->nullable();
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
        Schema::drop('t_umeiii_master');
    }
}
