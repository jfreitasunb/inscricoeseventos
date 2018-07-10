<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguraInscricaoVeraoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configura_inscricao_verao', function (Blueprint $table){
            $table->increments('id_inscricao_verao');
            $table->date('inicio_inscricao');
            $table->date('fim_inscricao');
            $table->string('tipo_evento', 7);
            $table->integer('ano_evento');
            $table->unsignedInteger('id_coordenador');
            $table->foreign('id_coordenador')->references('id_user')->on('users')->onDelete('cascade');
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
        Schema::drop('configura_inscricao_verao');
    }
}