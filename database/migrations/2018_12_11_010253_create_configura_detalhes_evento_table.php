<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfiguraDetalhesEventoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configura_detalhes_evento', function (Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('id_inscricao_evento');
            $table->foreign('id_inscricao_evento')->references('id_inscricao_evento')->on('configura_inscricao_evento')->onDelete('cascade');
            $table->text('titulo_evento')->nullable();
            $table->date('periodo_realizacao');

            
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
        Schema::drop('configura_detalhes_evento');
    }
}
