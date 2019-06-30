<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScoreSumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_score_sum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource');
            $table->integer('resource_id');
            $table->integer('score_sum'); //summarize of score
            $table->integer('score_count'); //number of users have given the score
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
        Schema::drop('t_score_sum');
    }
}

?>
