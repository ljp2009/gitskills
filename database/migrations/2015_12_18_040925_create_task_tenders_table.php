<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskTendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //招标属性，当任务类型为招标时，会在此表中生成一条记录
        Schema::create('t_task_tenders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->integer('max_targer_count')->default(0);//招标的目标数量, 0表示不做限制
            $table->integer('max_request_count')->default(0);//可以发起的申请数量, 0表示不做限制
            $table->text('requirement')->nullable();//对参与者的需求描述
            $table->integer('assign_solution')->default(0);//0表示采用默认的分配方案
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
        Schema::drop('t_task_tenders');
    }
}
