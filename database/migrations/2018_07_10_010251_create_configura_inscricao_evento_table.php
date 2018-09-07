<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguraInscricaoEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configura_inscricao_evento', function (Blueprint $table){
            $table->increments('id_inscricao_evento');
            $table->date('inicio_inscricao');
            $table->date('fim_inscricao');
            $table->unsignedInteger('id_tipo_evento');
            $table->foreign('id_tipo_evento')->references('id')->on('tipo_evento')->onDelete('cascade');
            $table->unsignedInteger('id_area_evento')->nullable();
            $table->foreign('id_area_evento')->references('id_area_pos')->on('area_pos_mat')->onDelete('cascade');
            $table->text('nome_evento');
            $table->integer('ano_evento');
            $table->unsignedInteger('id_coordenador');
            $table->foreign('id_coordenador')->references('id_user')->on('users')->onDelete('cascade');
            $table->boolean('selecao_trabalhos_finalizada')->default('0');
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
        Schema::drop('configura_inscricao_evento');
    }
}