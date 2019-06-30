<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpSceneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_scene', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('ip_id');
            $table->string('text');
			$table->string('image')->nullable();
			$table->integer('verified');//n=0, y=1
			$table->string('verified_by')->nullable();
			$table->timestamp('verified_at')->nullable();
            $table->integer('like_sum')->default(0);
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
        Schema::drop('t_ip_scene');
    }
}
