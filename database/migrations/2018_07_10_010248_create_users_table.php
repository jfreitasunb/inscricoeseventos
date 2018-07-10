<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table){
            $table->increments('id_user');
            $table->string('nome')->nullable();
            $table->string('email')->unique();
            $table->string('locale', 5)->default('pt-br');
            $table->string('password');
            $table->string('validation_code')->nullable();
            $table->string('user_type','12')->default('candidato');
            $table->boolean('ativo')->default('0');
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
