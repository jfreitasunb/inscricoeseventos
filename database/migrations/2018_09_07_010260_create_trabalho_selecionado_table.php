<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrabalhoSelecionadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabalho_selecionado', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_participante');
            $table->foreign('id_participante')->references('id_user')->on('users')->onDelete('cascade');
            $table->unsignedInteger('id_categoria_participante');
            $table->foreign('id_categoria_participante')->references('id')->on('configura_categoria_participante')->onDelete('cascade');
            $table->unsignedInteger('id_tipo_apresentacao');
            $table->foreign('id_tipo_apresentacao')->references('id')->on('configura_tipo_apresentacao')->onDelete('cascade');
            $table->unsignedInteger('id_area_trabalho');
            $table->foreign('id_area_trabalho')->references('id_area_pos')->on('area_pos_mat')->onDelete('cascade');
            $table->unsignedInteger('id_inscricao_evento');
            $table->foreign('id_inscricao_evento')->references('id_inscricao_evento')->on('configura_inscricao_evento')->onDelete('cascade');
            $table->unsignedInteger('id_coordenador');
            $table->foreign('id_coordenador')->references('id_user')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::drop('trabalho_selecionado');
    }
}
