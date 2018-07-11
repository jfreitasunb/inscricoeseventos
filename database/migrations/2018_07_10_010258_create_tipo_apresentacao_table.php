<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoApresentacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_apresentacao', function (Blueprint $table){
            $table->increments('id');
            $table->text('nome_tipo_apresentacao_ptbr', 15)->nulable();
            $table->text('nome_tipo_apresentacao_en', 15)->nulable();
            $table->text('nome_tipo_apresentacao_es', 15)->nulable();
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
        Schema::drop('tipo_apresentacao');
    }
}
