<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyUserPrivateLetterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('t_user_private_letter', function (Blueprint $table) {
            $table->enum('type',array('personal','system','invite'))->after('status')->default('personal');
        });
      
    }
    public function down()
    {
    }
}
