<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinalizaInscricaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finaliza_inscricao', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_user')->on('users')->onDelete('cascade');
            $table->unsignedInteger('id_inscricao_verao');
            $table->foreign('id_inscricao_verao')->references('id_inscricao_verao')->on('configura_inscricao_verao')->onDelete('cascade');
            $table->boolean('finalizada');
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
        Schema::drop('finaliza_inscricao');
    }
}
