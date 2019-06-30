<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProdSellInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_prod_sell_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prod_id');
            $table->integer('user_id');
            $table->decimal('price', 5, 2);//售卖价格
            $table->text('sell_intro');//售卖说明
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
        Schema::drop('t_user_prod_sell_info');
    }
}
