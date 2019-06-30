<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserProduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_user_production', function (Blueprint $table) {
            if(!Schema::hasColumn('t_user_production','link')){
                $table->text('link');//链接
            }
            if(!Schema::hasColumn('t_user_production','relate_type')){
                $table->enum('relate_type',['coll','peri'])->nullable;//与IP的关系同人或者周边
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
        Schema::table('t_user_production', function (Blueprint $table) {
        });
    }
}
