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
            $table->unsignedInteger('id_categoria_participante');
            $table->foreign('id_categoria_participante')->references('id')->on('categoria_participante')->onDelete('cascade');
            $table->unsignedInteger('id_inscricao_evento');
            $table->foreign('id_inscricao_evento')->references('id_inscricao_evento')->on('configura_inscricao_evento')->onDelete('cascade');
            $table->boolean('apresentar_trabalho');
            $table->unsignedInteger('id_tipo_apresentacao')->nullable();
            $table->foreign('id_tipo_apresentacao')->references('id')->on('tipo_apresentacao')->onDelete('cascade');
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
