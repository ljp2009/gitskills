<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user', function (Blueprint $table) {
            $table->increments('id');
			$table->string('password');
            $table->string('display_name');
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('wechat_token')->nullable();
            $table->text('avatar')->nullable();
            $table->text('background')->nullable();
            $table->enum('status',array('registed','activated','locked'));
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_user');
    }
}
