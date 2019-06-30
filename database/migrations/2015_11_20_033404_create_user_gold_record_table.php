<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserGoldRecordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_gold_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('resource');
            $table->integer('resource_id');
            $table->enum('type', array('income','pay'));
            $table->decimal('gold', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('t_user_gold_record');
    }
}
