<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::create('t_user_skill', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('user_id');
    		$table->string('code');
    		$table->integer('level');
    		$table->integer('score');
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
        Schema::drop('t_user_skill');
    }
}
