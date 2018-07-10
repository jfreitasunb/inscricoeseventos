<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDadosPessoaisParticipanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dados_pessoais_participante', function (Blueprint $table){
            $table->Increments('id');
            $table->unsignedInteger('id_participante');
            $table->foreign('id_participante')->references('id_user')->on('users')->onDelete('cascade');
            $table->string('instituicao_recomendante',500)->nullable();
            $table->string('numerorg',30)->nullable();
            $table->string('endereco',255)->nullable();
            $table->string('cep',30)->nullable();
            $table->integer('pais')->nullable();
            $table->integer('estado')->nullable();
            $table->integer('cidade')->nullable();
            $table->string('celular',20)->nullable();
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
        Schema::drop('dados_pessoais_participante');
    }
}