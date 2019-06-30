<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisitLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('t_visit_log')) return;
        Schema::create('t_visit_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('page');
            $table->string('ip')->nullable();
            $table->string('mobile')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('action')->nullable();
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
        Schema::drop('t_visit_log');
    }
}
