<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriaParticipanteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria_participante', function (Blueprint $table){
            $table->increments('id');
            $table->text('nome_categoria_ptbr', 15)->nulable();
            $table->text('nome_categoria_en', 15)->nulable();
            $table->text('nome_categoria_es', 15)->nulable();
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
        Schema::drop('categoria_participante');
    }
}
