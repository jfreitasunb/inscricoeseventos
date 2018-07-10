<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formacao', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_ptbr',50);
            $table->string('tipo_en',50)->nullable();
            $table->string('tipo_es',50)->nullable();
            $table->string('nivel',50)->nullable();
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
        Schema::dropIfExists('formacao');
    }
}
