<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpPeripheralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_peripheral', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ip_id');
            $table->string('title')->nullable();
            $table->text('image')->nullable();
            $table->text('text')->nullable();
            $table->integer('user_id');
            $table->integer('verified');//n=0, y=1
            $table->string('verified_by')->nullable();
            $table->timestamp('verified_at')->nullable();
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
        Schema::drop('t_ip_peripheral');
    }
}
