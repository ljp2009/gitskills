<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserWeixinParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_user', function (Blueprint $table) {
            if(!Schema::hasColumn('t_user','wx_open_id')){
                $table->string('wx_open_id');//
            }
            if(!Schema::hasColumn('t_user','wx_union_id')){
                $table->string('wx_union_id');//链接
            }
            if(!Schema::hasColumn('t_user','deleted_at')){
                $table->softDeletes();//软删除
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_user', function (Blueprint $table) {
            $table->dropColumn('wx_union_id', 'wx_open_id');
        });
    }
}
