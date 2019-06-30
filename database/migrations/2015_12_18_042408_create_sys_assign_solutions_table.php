<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysAssignSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //招标模式下，参与者的分配方案
        Schema::create('sys_assign_solutions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('code');//彪悍
            $table->boolean('default');//是否默认
            $table->string('name');//名称
            $table->text('description');//描述
            $table->text('formula');//公式（计算分配方式的一些辅助规则参数，公式格式待定~~）
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
        Schema::drop('sys_assign_solutions');
    }
}
