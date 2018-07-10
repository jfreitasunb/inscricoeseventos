<?php

use Illuminate\Database\Seeder;

class ConfiguraInscricaoPosTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('configura_inscricao_pos')->delete();
        
        \DB::table('configura_inscricao_pos')->insert(array (
            0 => 
            array (
                'id_inscricao_pos' => 1,
                'inicio_inscricao' => '2012-10-15',
                'fim_inscricao' => '2012-11-01',
                'prazo_carta' => '2012-11-05',
                'programa' => '1_2',
                'edital' => '2012-2',
                'id_coordenador' => 2,
                'created_at' => '2012-10-10 14:00:00',
                'updated_at' => '2012-10-10 14:00:00',
            ),
            1 => 
            array (
                'id_inscricao_pos' => 2,
                'inicio_inscricao' => '2013-02-25',
                'fim_inscricao' => '2013-03-09',
                'prazo_carta' => '2013-03-12',
                'programa' => '1',
                'edital' => '2013-1',
                'id_coordenador' => 2,
                'created_at' => '2013-02-20 14:00:00',
                'updated_at' => '2013-02-20 14:00:00',
            ),
            2 => 
            array (
                'id_inscricao_pos' => 3,
                'inicio_inscricao' => '2013-05-27',
                'fim_inscricao' => '2013-06-07',
                'prazo_carta' => '2013-03-08',
                'programa' => '1_2',
                'edital' => '2013-2',
                'id_coordenador' => 2,
                'created_at' => '2013-05-20 14:00:00',
                'updated_at' => '2013-05-20 14:00:00',
            ),
            3 => 
            array (
                'id_inscricao_pos' => 4,
                'inicio_inscricao' => '2013-09-30',
                'fim_inscricao' => '2013-11-11',
                'prazo_carta' => '2013-11-15',
                'programa' => '1_2',
                'edital' => '2013-3',
                'id_coordenador' => 2,
                'created_at' => '2013-09-20 14:00:00',
                'updated_at' => '2013-09-20 14:00:00',
            ),
            4 => 
            array (
                'id_inscricao_pos' => 5,
                'inicio_inscricao' => '2014-02-17',
                'fim_inscricao' => '2014-02-27',
                'prazo_carta' => '2014-02-28',
                'programa' => '1_2',
                'edital' => '2014-1',
                'id_coordenador' => 2,
                'created_at' => '2014-02-10 14:00:00',
                'updated_at' => '2014-02-10 14:00:00',
            ),
            5 => 
            array (
                'id_inscricao_pos' => 6,
                'inicio_inscricao' => '2014-05-02',
                'fim_inscricao' => '2014-05-30',
                'prazo_carta' => '2014-06-01',
                'programa' => '1_2',
                'edital' => '2014-2',
                'id_coordenador' => 2,
                'created_at' => '2014-04-25 14:00:00',
                'updated_at' => '2014-04-25 14:00:00',
            ),
            6 => 
            array (
                'id_inscricao_pos' => 7,
                'inicio_inscricao' => '2014-10-01',
                'fim_inscricao' => '2014-11-02',
                'prazo_carta' => '2014-11-10',
                'programa' => '1_2',
                'edital' => '2014-3',
                'id_coordenador' => 2,
                'created_at' => '2014-09-25 14:00:00',
                'updated_at' => '2014-09-25 14:00:00',
            ),
            7 => 
            array (
                'id_inscricao_pos' => 8,
                'inicio_inscricao' => '2015-02-09',
                'fim_inscricao' => '2015-02-20',
                'prazo_carta' => '2015-02-22',
                'programa' => '1',
                'edital' => '2015-1',
                'id_coordenador' => 2,
                'created_at' => '2015-02-02 14:00:00',
                'updated_at' => '2015-02-02 14:00:00',
            ),
            8 => 
            array (
                'id_inscricao_pos' => 9,
                'inicio_inscricao' => '2015-05-04',
                'fim_inscricao' => '2015-05-28',
                'prazo_carta' => '2015-06-01',
                'programa' => '1_2',
                'edital' => '2015-2',
                'id_coordenador' => 2,
                'created_at' => '2015-04-20 14:00:00',
                'updated_at' => '2015-04-20 14:00:00',
            ),
            9 => 
            array (
                'id_inscricao_pos' => 10,
                'inicio_inscricao' => '2015-10-05',
                'fim_inscricao' => '2015-11-06',
                'prazo_carta' => '2015-11-10',
                'programa' => '1_2',
                'edital' => '2015-3',
                'id_coordenador' => 2,
                'created_at' => '2015-09-20 14:00:00',
                'updated_at' => '2015-09-20 14:00:00',
            ),
            10 => 
            array (
                'id_inscricao_pos' => 11,
                'inicio_inscricao' => '2016-01-25',
                'fim_inscricao' => '2016-02-03',
                'prazo_carta' => '2016-02-06',
                'programa' => '1',
                'edital' => '2016-1',
                'id_coordenador' => 2,
                'created_at' => '2016-01-20 14:00:00',
                'updated_at' => '2016-01-20 14:00:00',
            ),
            11 => 
            array (
                'id_inscricao_pos' => 12,
                'inicio_inscricao' => '2016-05-09',
                'fim_inscricao' => '2016-05-30',
                'prazo_carta' => '2016-06-04',
                'programa' => '1_2',
                'edital' => '2016-2',
                'id_coordenador' => 2,
                'created_at' => '2016-04-20 14:00:00',
                'updated_at' => '2016-04-20 14:00:00',
            ),
            12 => 
            array (
                'id_inscricao_pos' => 13,
                'inicio_inscricao' => '2016-09-26',
                'fim_inscricao' => '2016-10-20',
                'prazo_carta' => '2016-10-28',
                'programa' => '1_2',
                'edital' => '2016-3',
                'id_coordenador' => 2,
                'created_at' => '2016-09-20 14:00:00',
                'updated_at' => '2016-09-20 14:00:00',
            ),
            13 => 
            array (
                'id_inscricao_pos' => 14,
                'inicio_inscricao' => '2017-02-01',
                'fim_inscricao' => '2017-02-10',
                'prazo_carta' => '2017-02-15',
                'programa' => '1',
                'edital' => '2017-1',
                'id_coordenador' => 2,
                'created_at' => '2017-01-20 14:00:00',
                'updated_at' => '2017-01-20 14:00:00',
            ),
            14 => 
            array (
                'id_inscricao_pos' => 15,
                'inicio_inscricao' => '2017-04-10',
                'fim_inscricao' => '2017-05-08',
                'prazo_carta' => '2017-05-16',
                'programa' => '1_2',
                'edital' => '2017-2',
                'id_coordenador' => 2,
                'created_at' => '2017-03-20 14:00:00',
                'updated_at' => '2017-03-20 14:00:00',
            ),
            15 => 
            array (
                'id_inscricao_pos' => 16,
                'inicio_inscricao' => '2017-09-11',
                'fim_inscricao' => '2017-10-13',
                'prazo_carta' => '2017-10-21',
                'programa' => '1_2',
                'edital' => '2017-3',
                'id_coordenador' => 2,
                'created_at' => '2017-08-20 14:00:00',
                'updated_at' => '2017-08-20 14:00:00',
            ),
            16 => 
            array (
                'id_inscricao_pos' => 17,
                'inicio_inscricao' => '2017-11-11',
                'fim_inscricao' => '2017-12-31',
                'prazo_carta' => '2018-01-06',
                'programa' => '1_2',
                'edital' => '2017-4',
                'id_coordenador' => 2,
                'created_at' => '2017-08-20 14:00:00',
                'updated_at' => '2017-08-20 14:00:00',
            ),
        ));
        
        $tableToCheck = 'configura_inscricao_pos';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_inscricao_pos)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_inscricao_pos_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_inscricao_pos_seq\', '.$highestId->max.')');    
        
    }
}