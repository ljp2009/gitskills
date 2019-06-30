<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskDeliveryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('t_task_delivery', function (Blueprint $table) {
    		$table->increments('id');
    		$table->integer('task_id');
    		$table->integer('user_id');
    		$table->string('image');
    		$table->string('attachment');//附件
    		$table->text('text');
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
        Schema::drop('t_task_delivery');
    }
}
