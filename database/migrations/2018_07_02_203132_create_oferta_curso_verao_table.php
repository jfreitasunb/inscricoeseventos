<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfertaCursoVeraoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oferta_curso_verao', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_inscricao_verao');
            $table->foreign('id_inscricao_verao')->references('id_inscricao_verao')->on('configura_inscricao_verao')->onDelete('cascade');
            $table->unsignedInteger('id_curso_verao');
            $table->foreign('id_curso_verao')->references('id_curso_verao')->on('curso_verao_mat')->onDelete('cascade');
            $table->boolean('seleciona_pos')->default('0');
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
        Schema::dropIfExists('oferta_curso_verao');
    }
}
