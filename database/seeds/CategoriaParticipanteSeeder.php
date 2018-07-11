<?php

use Illuminate\Database\Seeder;

class CategoriaParticipanteSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categoria_participante')->delete();
        
        \DB::table('categoria_participante')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nome_categoria_ptbr' => 'Professor',
                'nome_categoria_en' => 'Professor',
                'nome_categoria_es' => 'Profesor',
                'created_at' => '2018-07-10 15:27:00',
                'updated_at' => '2018-07-10 15:27:00',
            ),
            1 => 
            array (
                'id' => 2,
                'nome_categoria_ptbr' => 'Estudante',
                'nome_categoria_en' => 'Student',
                'nome_categoria_es' => 'Estudiante',
                'created_at' => '2018-07-10 15:27:00',
                'updated_at' => '2018-07-10 15:27:00',
            ),
        ));
        
        $tableToCheck = 'categoria_participante';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        
    }
}