<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_role', function (Blueprint $table) {
            $table->increments('id');
             $table->integer('ip_id');
             $table->string('name');
             $table->text('intro');
             $table->text('header');
             $table->text('image');
             $table->integer('creator');
             $table->integer('mender');
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
        Schema::drop('t_ip_role');
    }
}
