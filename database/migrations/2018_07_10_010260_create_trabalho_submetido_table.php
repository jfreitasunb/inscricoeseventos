<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoParticipacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_participacao', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_participante');
            $table->foreign('id_participante')->references('id_user')->on('users')->onDelete('cascade');
            $table->unsignedInteger('id_area_trabalho');
            $table->foreign('id_participante')->references('id_area_pos')->on('area_pos_mat')->onDelete('cascade');
            $table->unsignedInteger('id_inscricao_evento');
            $table->foreign('id_inscricao_evento')->references('id_inscricao_evento')->on('configura_inscricao_evento')->onDelete('cascade');
            $table->text('titulo_trabalho');
            $table->text('autor_trabalho');
            $table->text('abstract_trabalho');
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
        Schema::drop('tipo_participacao');
    }
}
