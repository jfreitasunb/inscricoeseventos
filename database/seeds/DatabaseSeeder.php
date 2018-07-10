<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        $this->call(TipoEventoMatTableSeeder::class);
        $this->call(CursoVeraoMatTableSeeder::class);
        $this->call(PaisesTableSeeder::class);
        $this->call(EstadosTableSeeder::class);
        $this->call(CidadesTableSeeder::class);
        

        $user = ['nome' => 'Jota', 'email' => 'jfreitas.mat@gmail.com', 'password' => bcrypt('1'), 'user_type' => 'admin' , 'ativo' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        $db_user = DB::table('users')->insert($user);

        $user = ['nome' => 'Coordenação de Evento', 'email' => 'coordenador@mat.unb.br', 'password' => bcrypt('1'), 'user_type' => 'coordenador' , 'ativo' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        $db_user = DB::table('users')->insert($user);

    }
}
