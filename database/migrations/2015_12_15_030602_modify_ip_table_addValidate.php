<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyIpTableAddValidate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_ip', function (Blueprint $table) {
            $table->boolean('validated')->defalut(false);
            $table->integer('validator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('t_ip', function (Blueprint $table) {
            $table->dropColumn(['validated','validator']);
        });
    }
}
