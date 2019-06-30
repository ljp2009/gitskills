<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInviteUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('t_invite_user', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id');//用户id
            $table->integer('resource_id');//邀请id（t_invite）
            $table->integer('type');//邀请源方式（邀请还是范围邀请）
            $table->integer('statue')->default('0');//是否接受邀请
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
