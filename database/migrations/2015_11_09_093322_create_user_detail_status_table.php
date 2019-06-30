<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_detail_status', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('user_id');
             $table->integer('score');
             $table->integer('level');
             $table->decimal('gold', 9, 2);
             $table->decimal('receive_gold', 9, 2);
             $table->integer('character');
             $table->integer('position');
             $table->integer('behavior');
             $table->boolean('is_expert');
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
        Schema::drop('t_user_detail_status'); 
    }
}
