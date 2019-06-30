<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_task_timeline', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('user_id')->defalult(0);//创建者，0标识系统创建的
            $table->enum('type',array('state','milestone','system'))->defalult('system');
            $table->enum('status', array('wait','finish','delay', 'failed'))->defalult('wait');//等待到达，按时到达，延迟到达，我发到达
            $table->date('expect_date');//预期时间
            $table->date('finish_date')->nullable();//实际完成时间
            $table->text('result')->nullable();//结果（完成时填写）
            $table->text('intro')->nullable();//简述(创建时候填写);
            $table->text('image')->nullable();//链接图片
            $table->string('name');
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
        Schema::drop('t_task_timeline');
    }
}
