<?php

use Illuminate\Database\Seeder;

class CursoVeraoMatTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('curso_verao_mat')->delete();
        
        \DB::table('curso_verao_mat')->insert(array (
            0 => 
            array (
                'id_curso_verao' => 1,
                'nome_ptbr' => 'Não Ofertado',
                'nome_en' => 'Não Ofertado',
                'nome_es' => 'Não Ofertado',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
        ));

        $tableToCheck = 'curso_verao_mat';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_curso_verao)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_curso_verao_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_curso_verao_seq\', '.$highestId->max.')');   
    }
}