<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysUserOrderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_user_order_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('work_batch');
            //起始操作
            $table->integer('action_id');
            $table->integer('user_id');
            $table->string('user_ip');
            $table->datetime('action_time');
            //结束操作
            $table->integer('next_action')->nullable();
            $table->integer('next_user_id')->nullable();
            $table->string('next_user_ip')->nullable();
            $table->datetime('next_action_time')->nullable();
            //计算信息
            $table->integer('action_second')->nullable();
            $table->integer('is_end')->nullable();
            $table->string('session')->nullable();
            $table->string('session_day')->nullable();
            $table->string('session_week')->nullable();
            $table->string('session_month')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('_sys_user_order_log');
    }
}
