<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLongDiscussionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_long_discussion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('resource');
            $table->string('title');
            $table->integer('resource_id');
            $table->text('text');
            $table->string('image');
            $table->integer('reference_id');
            $table->integer('response_id');
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
        Schema::drop('t_long_discussion');
    }
}
