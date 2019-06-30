<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySysTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sys_tag', function (Blueprint $table) {
            $table->string('code');
            $table->enum('depend',array('ip','cartoon','game','story'))->after('code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sys_tag', function (Blueprint $table) {
            $table->dropColumn(['depend']);
        });
    }
}
