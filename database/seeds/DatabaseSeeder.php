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
        // $this->call(UsersTableSeeder::class);
        // $this->call(ConfiguraInscricaoPosTableSeeder::class);
        $this->call(ProgramaPosMatTableSeeder::class);
        $this->call(CursoVeraoMatTableSeeder::class);
        // $this->call(FormacaoTableSeeder::class);
        $this->call(PaisesTableSeeder::class);
        $this->call(EstadosTableSeeder::class);
        $this->call(CidadesTableSeeder::class);
        // $this->call(DadosPessoaisTableSeeder::class);
        // $this->call(DadosRecomendantesTableSeeder::class);
        // $this->call(DadosAcademicosTableSeeder::class);
        // $this->call(ArquivosEnviadosTableSeeder::class);
        // $this->call(CartaMotivacoesTableSeeder::class);
        // $this->call(EscolhasCandidatoTableSeeder::class);
        // $this->call(ContatosRecomendantesTableSeeder::class);
        // $this->call(CartasRecomendacoesTableSeeder::class);
        // $this->call(FinalizaInscricaoTableSeeder::class);
        

        $user = ['nome' => 'Jota', 'email' => 'jfreitas.mat@gmail.com', 'password' => bcrypt('1'), 'user_type' => 'admin' , 'ativo' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        $db_user = DB::table('users')->insert($user);

        $user = ['nome' => 'Coordenação de Verão', 'email' => 'verao@mat.unb.br', 'password' => bcrypt('1'), 'user_type' => 'coordenador' , 'ativo' => '1', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        $db_user = DB::table('users')->insert($user);

        // $dados_jota = [
        //     'id_user' => '1',
        //     'data_nascimento' => '1979-05-28',
        //     'numerorg' => '1',
        //     'endereco' => '1',
        //     'cidade' => '1',
        //     'cep' => '1',
        //     'estado' => '1',
        //     'celular' => '1',
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s"),
        // ];
        // $db_dados_pessoais = DB::table('dados_pessoais_candidato')->insert($dados_jota);

        // $dados_coord = [
        //     'id_user' => '2',
        //     'data_nascimento' => '1963-04-01',
        //     'numerorg' => '2', 
        //     'endereco' => '2',
        //     'cidade' => '2',
        //     'cep' => '2',
        //     'estado' => '2',
        //     'celular' => '2',
        //     'created_at' => date("Y-m-d H:i:s"),
        //     'updated_at' => date("Y-m-d H:i:s"),
        // ];
        // $db_dados_coord = DB::table('dados_pessoais_candidato')->insert($dados_coord);

        // $configura_inscricao_pos_1 = ['inicio_inscricao' => '2012-10-15', 'fim_inscricao' => '2012-11-01', 'prazo_carta' => '2012-11-05','edital' => '2012-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2012-10-10 14:00:00', 'updated_at' => '2012-10-10 14:00:00'];

        // $db_configura_inscricao_pos_1 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_1);

        // $configura_inscricao_pos_2 = ['inicio_inscricao' => '2013-02-25', 'fim_inscricao' => '2013-03-09', 'prazo_carta' => '2013-03-12','edital' => '2013-1', 'programa' => '1', 'id_coordenador' => '2','created_at' => '2013-02-20 14:00:00', 'updated_at' => '2013-02-20 14:00:00'];

        // $db_configura_inscricao_pos_2 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_2);

        // $configura_inscricao_pos_3 = ['inicio_inscricao' => '2013-05-27', 'fim_inscricao' => '2013-06-07', 'prazo_carta' => '2013-03-08','edital' => '2013-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2013-05-20 14:00:00', 'updated_at' => '2013-05-20 14:00:00'];

        // $db_configura_inscricao_pos_3 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_3);

        // $configura_inscricao_pos_4 = ['inicio_inscricao' => '2013-09-30', 'fim_inscricao' => '2013-11-11', 'prazo_carta' => '2013-11-15','edital' => '2013-3', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2013-09-20 14:00:00', 'updated_at' => '2013-09-20 14:00:00'];

        // $db_configura_inscricao_pos_4 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_4);

        // $configura_inscricao_pos_5 = ['inicio_inscricao' => '2014-02-17', 'fim_inscricao' => '2014-02-27', 'prazo_carta' => '2014-02-28','edital' => '2014-1', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2014-02-10 14:00:00', 'updated_at' => '2014-02-10 14:00:00'];

        // $db_configura_inscricao_pos_5 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_5);


        // $configura_inscricao_pos_6 = ['inicio_inscricao' => '2014-05-02', 'fim_inscricao' => '2014-05-30', 'prazo_carta' => '2014-06-01','edital' => '2014-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2014-04-25 14:00:00', 'updated_at' => '2014-04-25 14:00:00'];

        // $db_configura_inscricao_pos_6 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_6);

        // $configura_inscricao_pos_7 = ['inicio_inscricao' => '2014-10-01', 'fim_inscricao' => '2014-11-02', 'prazo_carta' => '2014-11-10','edital' => '2014-3', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2014-09-25 14:00:00', 'updated_at' => '2014-09-25 14:00:00'];

        // $db_configura_inscricao_pos_7 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_7);

        // $configura_inscricao_pos_8 = ['inicio_inscricao' => '2015-02-09', 'fim_inscricao' => '2015-02-20', 'prazo_carta' => '2015-02-22','edital' => '2015-1', 'programa' => '1', 'id_coordenador' => '2','created_at' => '2015-02-02 14:00:00', 'updated_at' => '2015-02-02 14:00:00'];

        // $db_configura_inscricao_pos_8 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_8);

        // $configura_inscricao_pos_9 = ['inicio_inscricao' => '2015-05-04', 'fim_inscricao' => '2015-05-28', 'prazo_carta' => '2015-06-01','edital' => '2015-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2015-04-20 14:00:00', 'updated_at' => '2015-04-20 14:00:00'];

        // $db_configura_inscricao_pos_9 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_9);

        // $configura_inscricao_pos_10 = ['inicio_inscricao' => '2015-10-05', 'fim_inscricao' => '2015-11-06', 'prazo_carta' => '2015-11-10','edital' => '2015-3', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2015-09-20 14:00:00', 'updated_at' => '2015-09-20 14:00:00'];

        // $db_configura_inscricao_pos_10 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_10);

        // $configura_inscricao_pos_11 = ['inicio_inscricao' => '2016-01-25', 'fim_inscricao' => '2016-02-03', 'prazo_carta' => '2016-02-06','edital' => '2016-1', 'programa' => '1', 'id_coordenador' => '2','created_at' => '2016-01-20 14:00:00', 'updated_at' => '2016-01-20 14:00:00'];

        // $db_configura_inscricao_pos_11 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_11);


        // $configura_inscricao_pos_12 = ['inicio_inscricao' => '2016-05-09', 'fim_inscricao' => '2016-05-30', 'prazo_carta' => '2016-06-04','edital' => '2016-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2016-04-20 14:00:00', 'updated_at' => '2016-04-20 14:00:00'];

        // $db_configura_inscricao_pos_12 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_12);


        // $configura_inscricao_pos_13 = ['inicio_inscricao' => '2016-09-26', 'fim_inscricao' => '2016-10-20', 'prazo_carta' => '2016-10-28','edital' => '2016-3', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2016-09-20 14:00:00', 'updated_at' => '2016-09-20 14:00:00'];

        // $db_configura_inscricao_pos_13 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_13);

        // $configura_inscricao_pos_14 = ['inicio_inscricao' => '2017-02-01', 'fim_inscricao' => '2017-02-10', 'prazo_carta' => '2017-02-15','edital' => '2017-1', 'programa' => '1', 'id_coordenador' => '2','created_at' => '2017-01-20 14:00:00', 'updated_at' => '2017-01-20 14:00:00'];

        // $db_configura_inscricao_pos_14 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_14);

        // $configura_inscricao_pos_15 = ['inicio_inscricao' => '2017-04-10', 'fim_inscricao' => '2017-05-08', 'prazo_carta' => '2017-05-16','edital' => '2017-2', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2017-03-20 14:00:00', 'updated_at' => '2017-03-20 14:00:00'];

        // $db_configura_inscricao_pos_15 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_15);

        // $configura_inscricao_pos_16 = ['inicio_inscricao' => '2017-09-11', 'fim_inscricao' => '2017-10-13', 'prazo_carta' => '2017-10-21','edital' => '2017-3', 'programa' => '1_2', 'id_coordenador' => '2','created_at' => '2017-08-20 14:00:00', 'updated_at' => '2017-08-20 14:00:00'];

        // $db_configura_inscricao_pos_16 = DB::table('configura_inscricao_pos')->insert($configura_inscricao_pos_16);




        // $lista_areas_pos = [1 => 'Álgebra', 2 => 'Análise', 3 => 'Análise Numérica', 4 => 'Geometria', 5 => 'Matemática Aplicada', 6 => 'Probabilidade', 7 => 'Sistemas Dinâmicos', 8 => 'Teoria da Computação', 9 => 'Teoria dos Números'];

        // for ($i=1; $i < sizeof($lista_areas_pos)+1; $i++) { 
            
        //     $disciplina = ['id_area_pos' => $i, 'nome' => $lista_areas_pos[$i], 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        //     $db_disciplina = DB::table('area_pos_mat')->insert($disciplina);
        
    // }


        // $programa_pos1 = ['id_programa_pos' => 1, 'tipo_programa_pos' => 'Mestrado', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];

        // $db_programa_pos1 = DB::table('programa_pos_mat')->insert($programa_pos1);

        // $programa_pos2 = ['id_programa_pos' => 2, 'tipo_programa_pos' => 'Doutorado', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];

        // $db_programa_pos2 = DB::table('programa_pos_mat')->insert($programa_pos2);


        // $programa_pos3 = ['id_programa_pos' => 3, 'tipo_programa_pos' => 'Verão', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];

        // $db_programa_pos3 = DB::table('programa_pos_mat')->insert($programa_pos3);

        // $formacao1 = ['id' => 1, 'tipo' => 'Bacharelado', 'nivel' => 'Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao1 = DB::table('formacao')->insert($formacao1);

        // $formacao2 = ['id' => 2, 'tipo' => 'Licenciatura', 'nivel' => 'Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao2 = DB::table('formacao')->insert($formacao2);

        // $formacao3 = ['id' => 3, 'tipo' => 'Ambos', 'nivel' => 'Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao3 = DB::table('formacao')->insert($formacao3);

        // $formacao4 = ['id' => 4, 'tipo' => 'Não se Aplica', 'nivel' => 'Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao4 = DB::table('formacao')->insert($formacao4);

        // $formacao5 = ['id' => 5, 'tipo' => 'Acadêmico', 'nivel' => 'Pós-Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao5 = DB::table('formacao')->insert($formacao5);

        // $formacao6 = ['id' => 6, 'tipo' => 'Profissional', 'nivel' => 'Pós-Graduação', 'created_at' => date("Y-m-d H:i:s"), 'updated_at' => date("Y-m-d H:i:s")];
        // $db_formacao6 = DB::table('formacao')->insert($formacao6);

    }
}
