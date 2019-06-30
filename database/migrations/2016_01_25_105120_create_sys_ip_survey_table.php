<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysIpSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_ip_survey', function (Blueprint $table) {
    		$table->increments('id');
    		$table->enum('type', array('cartoon','story','game'));//1：动漫，2：小说，3：游戏
    		$table->string('image');//图片
    		$table->string('name');//名称
    		$table->text('intro');//介绍
    		$table->string('attrs');//属性
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
       Schema::drop('sys_ip_survey');
    }
}
