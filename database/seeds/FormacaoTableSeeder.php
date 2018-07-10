<?php

use Illuminate\Database\Seeder;

class FormacaoTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('formacao')->delete();
        
        \DB::table('formacao')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tipo_ptbr' => 'Bacharelado',
                'tipo_en' => 'Bach',
                'tipo_es' => 'Bach',
                'nivel' => 'Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            1 => 
            array (
                'id' => 2,
                'tipo_ptbr' => 'Licenciatura',
                'tipo_en' => 'Bach1',
                'tipo_es' => 'Bach12',
                'nivel' => 'Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            2 => 
            array (
                'id' => 3,
                'tipo_ptbr' => 'Ambos',
                'tipo_en' => 'Bach2323',
                'tipo_es' => 'Bach123',
                'nivel' => 'Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            3 => 
            array (
                'id' => 4,
                'tipo_ptbr' => 'Não se Aplica',
                'tipo_en' => 'Bach34243',
                'tipo_es' => 'Bach324234234',
                'nivel' => 'Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            4 => 
            array (
                'id' => 5,
                'tipo_ptbr' => 'Mestrado Acadêmico',
                'tipo_en' => 'Bach4523453',
                'tipo_es' => 'Bach232435',
                'nivel' => 'Pós-Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            5 => 
            array (
                'id' => 6,
                'tipo_ptbr' => 'Mestrado Profissional',
                'tipo_en' => 'Bach447657546',
                'tipo_es' => 'Bach87876757',
                'nivel' => 'Pós-Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            6 => 
            array (
                'id' => 7,
                'tipo_ptbr' => 'Doutorado',
                'tipo_en' => 'Bach09786587',
                'tipo_es' => 'Bach3567687',
                'nivel' => 'Pós-Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            7 => 
            array (
                'id' => 8,
                'tipo_ptbr' => 'Especialização',
                'tipo_en' => 'Bach7685798989',
                'tipo_es' => 'Bach675898987',
                'nivel' => 'Pós-Graduação',
                'created_at' => '2017-11-21 15:27:00',
                'updated_at' => '2017-11-21 15:27:00',
            ),
            // 8 => 
            // array (
            //     'id' => 9,
            //     'tipo' => 'Doutorado',
            //     'nivel' => 'Pós-Graduação',
            //     'created_at' => '2017-11-21 15:27:00',
            //     'updated_at' => '2017-11-21 15:27:00',
            // ),
        ));
        
        $tableToCheck = 'formacao';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        
    }
}