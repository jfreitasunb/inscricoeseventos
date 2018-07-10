<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEscolhasCursoVeraoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escolhas_curso_verao', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_user')->on('users')->onDelete('cascade');
            $table->integer('programa_pretendido');
            $table->unsignedInteger('curso_verao')->default(1);
            $table->foreign('curso_verao')->references('id_curso_verao')->on('curso_verao_mat')->onDelete('cascade');
            $table->unsignedInteger('id_inscricao_verao');
            $table->foreign('id_inscricao_verao')->references('id_inscricao_verao')->on('configura_inscricao_verao')->onDelete('cascade');
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
        Schema::drop('escolhas_curso_verao');
    }
}
