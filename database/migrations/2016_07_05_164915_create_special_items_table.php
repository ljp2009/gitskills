<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_special_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('special_id');
            $table->text('url')->nullable();
            $table->string('resource')->nullable();
            $table->integer('resource_id')->nullable();
            $table->string('type')->nullable();
            $table->text('intro')->nullable();
            $table->string('name')->nullable();
            $table->string('img')->nullable();
            $table->integer('creator');
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
        Schema::drop('t_special_item');
    }
}
