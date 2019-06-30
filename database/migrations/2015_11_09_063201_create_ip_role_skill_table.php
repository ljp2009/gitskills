<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIpRoleSkillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_ip_role_skill', function (Blueprint $table) {
            $table->increments('id');
             $table->integer('role_id');
             $table->string('name');
             $table->integer('skill_type');
             $table->text('image');
             $table->text('intro')->nullable();
             $table->boolean('is_main')->default(false);
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
        Schema::drop('t_ip_role_skill');
    }
}
