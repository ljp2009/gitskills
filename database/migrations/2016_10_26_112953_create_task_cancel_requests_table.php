<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskCancelRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_task_cancel_request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('user_id');
            $table->integer('type');
            $table->integer('status');
            $table->decimal('pay')->default(0); //需要支付给乙方的金币
            $table->integer('reason')->default(0); //取消原因
            $table->text('reason_text')->nullable(); //取消原因(发起方填写)
            $table->datetime('finish_date')->nullable(); //开始或者拒绝的时间
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
        Schema::drop('t_task_cancel_request');
    }
}
