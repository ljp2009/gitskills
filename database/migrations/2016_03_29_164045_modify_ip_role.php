<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyIpRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_ip_role', function (Blueprint $table) {
            $table->integer('user_id');
            $table->boolean('is_lock');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_ip_role', function (Blueprint $table) {
            $table->dropColumn('user_id','is_lock');
        });
    }
}
