<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
/**
 * 认证申请
 * @author admin
 *
 */
class CreateCertificationApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('t_certification_apply', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id');//用户id
            $table->integer('skill');//技能
            $table->integer('skill_level');//技能等级
            $table->text('instruction');//申请说明
            $table->text('ip_reference');//参考IP（多个）
            $table->integer('status');//状态(0:待审核，1:审核中，2:审核完成)
            $table->integer('result');//审核结果(1:通过，0:未通过)
            $table->text('remark');//备注
            $table->integer('delete');//逻辑删除(1:删除)
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
        Schema::drop('t_certification_apply');
    }
}
