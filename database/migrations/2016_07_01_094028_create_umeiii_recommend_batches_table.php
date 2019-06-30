<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUmeiiiRecommendBatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_umeiii_recommend_batch', function (Blueprint $table) {
            $table->increments('id');
            $table->string('batch_no');
            $table->date('publish_date');
            $table->string('user_id')->nullable();
            $table->string('intro')->nullable();
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
        Schema::drop('t_umeiii_recommend_batche');
    }
}
