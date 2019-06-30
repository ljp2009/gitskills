<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //创建任务表
        Schema::create('t_task', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title'); //标题
            $table->integer('user_id'); //发起者
            $table->text('intro'); //说明
            $table->text('image')->nullable(); //图片
            $table->text('tags')->nullable(); //标签，仅用于显示
            $table->decimal('amount')->default(0); //金额
            $table->enum('pay_type', array('coin', 'rmb'))->default('coin'); //支付方式, 金币或者人民币
            $table->boolean('is_crowdfunding')->default(false); //是否众筹
            $table->enum('task_type', array('simple', 'tenders'))->default('simple'); //简单模式，或者招标模式
            $table->integer('step')->default(0); //项目阶段:待审核, 待发布, 招标中，定标中，交付中，结算中，结束, 默认0为发布阶段，
            $table->date('delivery_date')->nullable(); //交付时间
            $table->boolean('cancel')->nullable(); //当为true的时候表示已经取消（用户可以在发布和招标阶段取消）
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
        Schema::drop('t_task');
    }
}
