<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArquivosEnviadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arquivos_enviados', function (Blueprint $table){
            $table->Increments('id');
            $table->unsignedInteger('id_candidato');
            $table->foreign('id_candidato')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('nome_arquivo',255);
            $table->string('tipo_arquivo',10);
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
        Schema::drop('arquivos_enviados');
    }
}
