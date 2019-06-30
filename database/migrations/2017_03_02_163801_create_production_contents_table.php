<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductionContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_production_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('production_id');
            $table->string('type')->default('text');
            $table->string('text')->nullable();
            $table->string('url')->nullable();
            $table->integer('order')->default(0);
            $table->integer('status')->default(1);
            $table->integer('related')->nullable();
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
        Schema::drop('t_production_content');
    }
    
