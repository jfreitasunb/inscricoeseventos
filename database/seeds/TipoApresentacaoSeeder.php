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
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
            1 => 
            array (
                'id' => 2,
                'nome_tipo_apresentacao_ptbr' => 'Poster',
                'nome_tipo_apresentacao_en' => 'Poster',
                'nome_tipo_apresentacao_es' => 'Poster',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            ),
        ));
        
        $tableToCheck = 'tipo_apresentacao';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        
    }
}