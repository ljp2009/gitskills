<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_task_invite', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->text('rule');//筛选规则,规则待定
            $table->integer('user_count');//推送的人数
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
        Schema::drop('t_task_invite');
    }
}
