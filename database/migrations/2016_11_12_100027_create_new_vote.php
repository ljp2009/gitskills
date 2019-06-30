<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewVote extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('t_vote')){
            Schema::drop('t_vote');
        }
        Schema::create('t_vote', function (Blueprint $table) {
    		$table->increments('id');
            $table->string('resource');//资源类型 task, activity
            $table->integer('type')->default(1);//类别，1:单个备选（第一轮）, 9:多个备选(第二轮)
            $table->integer('resource_id'); //对应的主资源id
            $table->string('alternatives');//备选项目（id类别或者其他规则）
            $table->integer('voted');//已经投票次数
            $table->string('batch');//批次，用于排序检索
            $table->integer('target');//目标投票次数
    		$table->text('result')->nullable();//1:第一轮  2:第二轮
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
        Schema::drop('t_vote');
    }
}
