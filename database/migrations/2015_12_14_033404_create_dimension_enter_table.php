<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionEnterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_dimension_enter', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('dimension_id');
             $table->string('user_id');
             $table->enum('is_enter', array('Y','N'));//是否入驻
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
        Schema::drop('t_dimension_enter');
    }
}
