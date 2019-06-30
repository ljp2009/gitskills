<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskPartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //任务参与者
        Schema::create('t_task_parter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('user_id');
            $table->integer('status')->default(1);//1:申请；2:参与；3:合作；4:拒绝；5:弃用（合作者或者参与者可以被发起者弃用，功能待整理）
            $table->text('request_description');//申请描述
            $table->text('reject_result');
            $table->text('image');//图片
            $table->string('authority');//对参与者/合作者的授权，功能待确定，例如：是否可以上传展示作品，是否可以发布动态等等
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
        Schema::drop('t_task_parter');
    }
}
