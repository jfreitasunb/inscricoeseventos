<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramaPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('programa_pos_mat', function (Blueprint $table){
            $table->increments('id_programa_pos');
            $table->string('tipo_programa_pos_ptbr',255);
            $table->string('tipo_programa_pos_en',255)->nullable();
            $table->string('tipo_programa_pos_es',255)->nullable();
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
        Schema::drop('programa_pos_mat');
    }
}
