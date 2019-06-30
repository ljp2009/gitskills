<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyActivityTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_activity', function (Blueprint $table) {
            $table->boolean('is_forbidden')->after('is_recommend');//交易结果（1:支出成功;2:支出失败;3:收入成功;4:收入失败)
            $table->tinyInteger('type')->after('is_recommend');
            $table->text('join_link')->after('is_recommend');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_activity', function (Blueprint $table) {
            $table->dropColumn('is_forbidden');
            $table->dropColumn('type');
            $table->dropColumn('join_link');
        });
    }
}
