<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserDetailStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('t_user_detail_status', function (Blueprint $table) {
            $table->decimal('pay_all', 9, 2)->after('receive_gold');
            $table->decimal('income_all', 9, 2)->after('pay_all');
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
