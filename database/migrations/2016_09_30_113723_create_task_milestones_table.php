<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskMilestonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_task_milestone', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('task_id');
            $table->text('text');
            $table->date('date');
            $table->enum('status',['wait', 'A', 'B', 'finish'])->defalult('wait');
            $table->date('a_sign_date')->nullable();
            $table->date('b_sign_date')->nullable();
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
        Schema::drop('t_task_milestone');
    }
}
