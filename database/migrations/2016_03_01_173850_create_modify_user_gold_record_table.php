<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModifyUserGoldRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_user_gold_record', function (Blueprint $table) {
            $table->dropColumn(['gold']);
        });
        Schema::table('t_user_gold_record', function (Blueprint $table) {
            $table->decimal('gold', 9, 2)->after('type');
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
