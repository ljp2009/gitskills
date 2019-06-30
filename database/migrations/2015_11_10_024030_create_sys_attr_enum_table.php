<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSysAttrEnumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sys_attr_enum', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('table_name');//if type== 'attr' then table_name = 'sys_attr'; 
                $table->string('column'); //if type== 'attr' then colum = 'attr_code';
                $table->integer('code');
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
        Schema::drop('sys_attr_enum');
    }
}
