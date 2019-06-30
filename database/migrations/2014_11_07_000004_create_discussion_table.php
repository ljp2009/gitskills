<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_discussion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('resource')->nullable();
            $table->integer('resource_id')->nullable();
            $table->text('text');
			$table->integer('reference_id')->nullable();
            $table->integer('response_id')->nullable();
			$table->string('images')->nullable();
			$table->integer('type');//short = 0, long = 1
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
        Schema::drop('t_discussion');
    }
}
