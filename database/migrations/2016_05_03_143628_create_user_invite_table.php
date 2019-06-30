<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
        Schema::create('t_invite', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id');//发送邀请者的用户id
            $table->integer('resource_id');//发送邀请源id（任务或活动id）
            $table->integer('resource_type');//邀请源类型（任务、活动）
            $table->integer('type');//邀请源方式（邀请还是范围邀请）
            $table->integer('user_count');//被邀请用户人数
            $table->string('user_id_arr');//被邀请用户id列表
            $table->string('rule');//邀请规则
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
        Schema::drop('t_user_invite');
    }
}
