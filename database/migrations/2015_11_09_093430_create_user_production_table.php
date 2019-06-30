<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProductionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_user_production', function (Blueprint $table) {
             $table->increments('id');
             $table->integer('ip_id'); //ip关联的IP，0 或者Null的时候不关联任何IP
             $table->integer('user_id');//创建者Id
             $table->string('name')->nullable();//作品名称
             $table->text('image');// 作品图片
             $table->boolean('is_original')->nullable(); //是否原创
             $table->boolean('is_sell')->nullable(); //是否售卖
             $table->text('intro');//简介
             $table->decimal('price', 5, 2)->nullable();//售卖价格
             $table->string('attr_code')->nullable();//属性类别
             $table->text('sell_intro')->nullable();//售卖说明
             $table->boolean('is_deleted')->nullable();//是否已经删除
             $table->integer('like_sum')->default(0);//创建者Id
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
        Schema::drop('t_user_production');
    }
}
