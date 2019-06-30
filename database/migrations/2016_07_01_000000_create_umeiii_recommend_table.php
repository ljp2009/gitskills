<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUmeiiiRecommendTable extends Migration
{
    /**
     * Run the migrations.
     * 资源大厅有妹推荐表
     * @return void
     */
    public function up()
    {
    	Schema::create('t_umeiii_recommend', function (Blueprint $table) {
    		$table->increments('id');
    		$table->string('name');//名称
    		$table->string('image');//图片
    		$table->string('intro');//推荐语
    		$table->integer('batch_id');//批次
    		$table->string('type');//类型
    		$table->integer('resource_id');//关联id
    		$table->integer('creator');//创建者
    		$table->integer('updator');//更新者
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
        Schema::drop('t_umeiii_recommend');
    }
}
