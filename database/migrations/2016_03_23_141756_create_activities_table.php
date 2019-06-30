<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_activity', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');//主题
            $table->text('text');//介绍
            $table->text('image')->nullable();//图片
            $table->string('from_date');//开始日期
            $table->string('to_date')->nullable();//结束时间
            $table->boolean('is_offline')->default(true);//是否线下活动
            $table->string('map')->nullable();//地图
            $table->string('address')->nullable();//地址
            $table->integer('scale')->default(0);//规模
            $table->text('link')->nullable();//活动链接
            $table->integer('user_id');//用户
            $table->boolean('is_recommend')->default(false);//是否推荐
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
        Schema::drop('t_activity');
    }
}
