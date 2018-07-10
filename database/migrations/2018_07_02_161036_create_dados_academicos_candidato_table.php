<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosAcademicosCandidatoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_academicos_candidato', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('curso_graduacao',255)->nullable();
            $table->unsignedInteger('tipo_curso_graduacao')->nullable();
            $table->foreign('tipo_curso_graduacao')->references('id')->on('formacao')->onDelete('cascade');
            $table->string('instituicao_graduacao',255)->nullable();
            $table->integer('ano_conclusao_graduacao')->nullable();
            $table->string('curso_pos',255)->nullable();
            $table->unsignedInteger('tipo_curso_pos')->nullable();
            $table->foreign('tipo_curso_pos')->references('id')->on('formacao')->onDelete('cascade');
            $table->string('instituicao_pos',255)->nullable();
            $table->integer('ano_conclusao_pos')->nullable();
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
        Schema::drop('dados_academicos_candidato');
    }
}
