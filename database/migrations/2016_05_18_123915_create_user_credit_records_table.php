<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_credit_record', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('value');
            $table->enum('type', ['score','level']);
            $table->string('category');
            $table->text('remark')->nullable();
            $table->integer('resource_id')->nullable();
            $table->integer('resource')->nullable();
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
        Schema::drop('t_user_credit_record');
    }
}
