<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAreaPosMatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_pos_mat', function (Blueprint $table){
            $table->increments('id_area_pos');
            $table->string('nome_ptbr',200);
            $table->string('nome_en',200)->nullable();
            $table->string('nome_es',200)->nullable();
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
        Schema::drop('area_pos_mat');
    }
}
