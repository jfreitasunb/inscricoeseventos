<?php

use Illuminate\Database\Seeder;

class TipoApresentacaoSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tipo_apresentacao')->delete();
        
        \DB::table('tipo_apresentacao')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nome_tipo_apresentacao_ptbr' => 'Palestra',
                'nome_tipo_apresentacao_en' => 'Lecture',
                'nome_tipo_apresentacao_es' => 'Palestra',
                'created_at' => '2018-07-10 15:27:00',
                'updated_at' => '2018-07-10 15:27:00',
            ),
            1 => 
            array (
                'id' => 2,
                'nome_tipo_apresentacao_ptbr' => 'Poster',
                'nome_tipo_apresentacao_en' => 'Poster',
                'nome_tipo_apresentacao_es' => 'Poster',
                'created_at' => '2018-07-10 15:27:00',
                'updated_at' => '2018-07-10 15:27:00',
            ),
        ));
        
        $tableToCheck = 'tipo_apresentacao';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        
    }
}