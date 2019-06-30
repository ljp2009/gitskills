<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserGoldRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	Schema::table('t_user_gold_record', function (Blueprint $table) {
            $table->tinyInteger('result')->after('gold');//交易结果（1:支出成功;2:支出失败;3:收入成功;4:收入失败)
            $table->string('remark')->after('result');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}
