<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifySysAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('sys_attr', function (Blueprint $table) {
            $table->dropColumn('data_type');
        });
        Schema::table('sys_attr', function (Blueprint $table) {
            $table->dropColumn('depend');
        });
        Schema::table('sys_attr', function (Blueprint $table) {
            $table->enum('data_type',array('label','number','enum','text','date'))->after('code');
    });
    Schema::table('sys_attr', function (Blueprint $table) {
            $table->enum('depend',array('ip','user','cartoon','game','story'))->after('data_type');
        });
    }
    public function down()
    {
    }
}
