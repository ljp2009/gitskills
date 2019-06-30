<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('t_validate_code')) return;
        Schema::create('t_validate_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('target');
            $table->integer('type')->default(0);
            $table->integer('status')->default(0);
            $table->integer('func')->default(0);
            $table->datetime('expire_time');
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
        Schema::drop('t_validate_code');
    }
}
