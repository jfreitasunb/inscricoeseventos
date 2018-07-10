<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursoVeraoMatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_verao_mat', function (Blueprint $table){
            $table->increments('id_curso_verao');
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
        Schema::drop('curso_verao_mat');
    }
}
