<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyTaskProperty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_task', function (Blueprint $table) {
            $table->date('publish_date');//发布日期
            $table->integer('delivery_type');//交付方式，1：线上 ;2:线下
            $table->string('skill_type');//技能分类
            $table->integer('skill_level');//技能级别，0表示不限制
            $table->integer('credit_level');//信誉级别，0表示不限制
            $table->integer('max_partner_count');//最大合作者数量，0表示不限制
            $table->integer('guarantee')->nullable();//担保人
            $table->integer('assign_solution');//分配方案
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_task', function (Blueprint $table) {
            $table->dropColumn('delivery_type','skill_type','skill_level',
                'credit_level','max_partner_count','guarantee','assign_solution');
        });
    }
}
