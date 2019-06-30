<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyLikeSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_like_sum', function (Blueprint $table) {
            $table->dropColumn(['resource']);
        });
        Schema::table('t_like_sum', function (Blueprint $table) {
            $table->string('resource')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
