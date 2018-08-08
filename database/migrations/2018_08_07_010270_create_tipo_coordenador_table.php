<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoCoordenadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_coordenador', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_coordenador');
            $table->foreign('id_coordenador')->references('id_user')->on('users')->onDelete('cascade');
            $table->boolean('coordenador_geral')->default('False');
            $table->unsignedInteger('coordenador_area')->nullable();
            $table->foreign('coordenador_area')->references('id_area_pos')->on('area_pos_mat')->onDelete('cascade');
            $table->unsignedInteger('id_evento');
            $table->foreign('id_evento')->references('id_inscricao_evento')->on('configura_inscricao_evento')->onDelete('cascade');
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
        Schema::drop('tipo_coordenador');
    }
}
