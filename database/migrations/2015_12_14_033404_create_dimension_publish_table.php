<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionPublishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_dimension_publish', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('dimension_id');
             $table->string('user_id');
             $table->string('image');
             $table->string('text');
             $table->integer('like_sum')->default(0);//创建者Id
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
        Schema::drop('t_dimension_publish');
    }
}
