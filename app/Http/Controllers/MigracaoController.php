<?php

namespace InscricoesEventos\Http\Controllers;

use Auth;
use DB;
use Mail;
use Session;
use File;
use ZipArchive;
use Purifier;
use PDF;
use InscricoesEventos\Http\Controllers\FPDFController;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\DadoPessoalCandidato;
use InscricoesEventos\Models\Paises;
use InscricoesEventos\Models\Formacao;
use InscricoesEventos\Models\Estado;
use InscricoesEventos\Models\Cidade;
use InscricoesEventos\Models\DadoPessoalRecomendante;
use InscricoesEventos\Models\DadoAcademicoCandidato;
use InscricoesEventos\Models\Documento;
use InscricoesEventos\Models\EscolhaCandidato;
use InscricoesEventos\Models\ContatoRecomendante;
use InscricoesEventos\Models\CartaMotivacao;
use InscricoesEventos\Models\CartaRecomendacao;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\ProgramaPos;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use UrlSigner;

/**
* Classe para visualização da página inicial.
*/
class MigracaoController extends BaseController
{
  public function getMigracao()
  {
        // Migra usuários para novo sistema

        $users = DB::connection('pos2')->table('users')->orderBy('id_user', 'asc')->get();

        foreach ($users as $user) {
            $users_candidato = DB::connection('pos2')->table('dados_pessoais')->where('id_user', $user->id_user)->first();
            
            $novo_usuario = new User();
            
            if (!is_null($users_candidato)) {
                $novo_usuario->nome = $users_candidato->nome;
            }
            $novo_usuario->id_user = $user->id_user;
            $novo_usuario->email = Purifier::clean(strtolower(trim($user->email)));
            $novo_usuario->locale = $user->locale;
            $novo_usuario->password = $user->password;
            $novo_usuario->validation_code = null;
            $novo_usuario->user_type = $user->user_type;
            $novo_usuario->ativo = $user->ativo;
            $novo_usuario->remember_token = null;
            $novo_usuario->created_at = $user->created_at;
            $novo_usuario->updated_at = $user->updated_at;
            $novo_usuario->save(); 
        }

        $tableToCheck = 'users';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_user)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_user_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_user_seq\', '.$highestId->max.')');
        
        //Fim da migração dos usuário para o novo sistema

        //Migra área da Pós para o novo sistema

        $areas_pos = DB::connection('pos2')->table('area_pos_mat')->orderBy('id_area_pos','asc')->get();

        foreach ($areas_pos as $area) {
            $nova_area = new AreaPosMat();

            $nova_area->id_area_pos = $area->id_area_pos;
            $nova_area->nome_ptbr = $area->nome;
            $nova_area->nome_en = $area->nome_en;
            $nova_area->nome_es = $area->nome_es;
            $nova_area->created_at = $area->created_at;
            $nova_area->updated_at = $area->updated_at;
            $nova_area->save();
        }
        
        $tableToCheck = 'area_pos_mat';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_area_pos)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_area_pos_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_area_pos_seq\', '.$highestId->max.')');

        //Fim da Migração das áreas da Pós  para o novo sistema
        
        // //Migra as formaçoes para o novo sistema

        $formacoes = DB::connection('pos2')->table('formacao')->orderBy('id','asc')->get();

        foreach ($formacoes as $formacao) {
            $nova_formacao = new Formacao();
            $nova_formacao->tipo_ptbr = $formacao->tipo;
            $nova_formacao->tipo_en = $formacao->tipo_en;
            $nova_formacao->tipo_es = $formacao->tipo_es;
            $nova_formacao->nivel = $formacao->nivel;
            $nova_formacao->created_at = $formacao->created_at;
            $nova_formacao->updated_at = $formacao->updated_at;
            $nova_formacao->save();
        }

        $tableToCheck = 'formacao';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // // //Fim da migração das formacoes para o novo sistema

        // // //Migra programas da Pós para o novo sistema.

        $programas_pos = DB::connection('pos2')->table('programa_pos_mat')->orderBy('id_programa_pos','asc')->get();

        foreach ($programas_pos as $programa_pos) {
            
            $novo_programa_pos = new ProgramaPos();

            $novo_programa_pos->id_programa_pos = $programa_pos->id_programa_pos;
            $novo_programa_pos->tipo_programa_pos_ptbr = $programa_pos->tipo_programa_pos;
            $novo_programa_pos->tipo_programa_pos_en = $programa_pos->tipo_programa_pos_en;
            $novo_programa_pos->tipo_programa_pos_es = $programa_pos->tipo_programa_pos_es;
            $novo_programa_pos->created_at = $programa_pos->created_at;
            $novo_programa_pos->updated_at = $programa_pos->updated_at;
            $novo_programa_pos->save();
        }
        
        $tableToCheck = 'programa_pos_mat';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_programa_pos)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_programa_pos_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_programa_pos_seq\', '.$highestId->max.')');

        // //Fim da migração dos programas da Pós para o novo sistema.

        //Migra as inscrições já configuradas para o novo sistema

        $inscricoes_configuradas = DB::connection('pos2')->table('configura_inscricao_pos')->orderBy('id_inscricao_pos','asc')->get();

        foreach ($inscricoes_configuradas as $inscricao) {
            
            $nova_inscricao_configurada = new ConfiguraInscricaoEvento();

            $nova_inscricao_configurada->id_inscricao_pos = $inscricao->id_inscricao_pos;
            $nova_inscricao_configurada->inicio_inscricao = $inscricao->inicio_inscricao;
            $nova_inscricao_configurada->fim_inscricao = $inscricao->fim_inscricao;
            $nova_inscricao_configurada->prazo_carta = $inscricao->prazo_carta;
            $nova_inscricao_configurada->programa = $inscricao->programa;
            $nova_inscricao_configurada->edital = $inscricao->edital;
            $nova_inscricao_configurada->id_coordenador = $inscricao->id_coordenador;
            $nova_inscricao_configurada->created_at = $inscricao->created_at;
            $nova_inscricao_configurada->updated_at = $inscricao->updated_at;
            $nova_inscricao_configurada->save();
        }
        
        $tableToCheck = 'configura_inscricao_pos';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id_inscricao_pos)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_inscricao_pos_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_inscricao_pos_seq\', '.$highestId->max.')');

        // //Fim da migração das inscrições já configuradas para o novo sistema.

        // // //Migra os arquivos enviados pelo candidatos

        $arquivos_enviados = DB::connection('pos2')->table('arquivos_enviados')->orderBy('id','asc')->get();

        foreach ($arquivos_enviados as $arquivo) {

            $novo_arquivos_enviados = new Documento();

            $novo_arquivos_enviados->id = $arquivo->id;
            $novo_arquivos_enviados->id_candidato = $arquivo->id_user;
            $novo_arquivos_enviados->nome_arquivo = $arquivo->nome_arquivo;
            $novo_arquivos_enviados->tipo_arquivo = $arquivo->tipo_arquivo;
            $novo_arquivos_enviados->id_inscricao_pos = $arquivo->id_inscricao_pos;
            $novo_arquivos_enviados->created_at = $arquivo->created_at;
            $novo_arquivos_enviados->updated_at = $arquivo->updated_at;
            $novo_arquivos_enviados->save();
        }

        $tableToCheck = 'arquivos_enviados';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // //Fim da migração dos arquivos enviados pelo candidatos

        // //Migra as cartas de motivação dos candidatos

        $carta_motivacao = DB::connection('pos2')->table('carta_motivacoes')->orderBy('id','asc')->get();


        foreach ($carta_motivacao as $motivacao) {
            

            $nova_carta_motivacao = new CartaMotivacao();

            $nova_carta_motivacao->id = $motivacao->id;
            $nova_carta_motivacao->id_candidato = $motivacao->id_user;
            $nova_carta_motivacao->motivacao = $motivacao->motivacao;
            $nova_carta_motivacao->concorda_termos = $motivacao->concorda_termos;
            $nova_carta_motivacao->id_inscricao_pos = $motivacao->id_inscricao_pos;
            $nova_carta_motivacao->created_at = $motivacao->created_at;
            $nova_carta_motivacao->updated_at = $motivacao->updated_at;
            $nova_carta_motivacao->save();

        }

        $tableToCheck = 'carta_motivacoes';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // //Fim da migração das cartas de motivação dos candidatos

        //Migra os contatos dos recomendantes

        $recomendantes_indicados = DB::connection('pos2')->table('contatos_recomendantes')->orderBy('id','asc')->get();

        foreach ($recomendantes_indicados as $recomendante) {
            
            $novo_contatos_recomendante = new ContatoRecomendante();

            $novo_contatos_recomendante->id = $recomendante->id;
            $novo_contatos_recomendante->id_candidato = $recomendante->id_user;
            $novo_contatos_recomendante->id_recomendante = $recomendante->id_recomendante;
            $novo_contatos_recomendante->id_inscricao_pos = $recomendante->id_inscricao_pos;
            $novo_contatos_recomendante->email_enviado = $recomendante->email_enviado;
            $novo_contatos_recomendante->created_at = $recomendante->created_at;
            $novo_contatos_recomendante->updated_at = $recomendante->updated_at;
            $novo_contatos_recomendante->save();
            
        }

        $tableToCheck = 'contatos_recomendantes';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        //Fim da migração dos contatos dos recomendantes
        
        // //Início da migração dos dados acadêmicos do candidato
        
        $dados_academicos_candidato = DB::connection('pos2')->table('dados_academicos')->orderBy('id','asc')->get();


        foreach ($dados_academicos_candidato as $academico_candidato) {

            $novo_dados_academicos_candidato = new DadoAcademicoCandidato();

            $novo_dados_academicos_candidato->id = $academico_candidato->id;
            $novo_dados_academicos_candidato->id_candidato = $academico_candidato->id_user;
            $novo_dados_academicos_candidato->curso_graduacao = $academico_candidato->curso_graduacao;
            $novo_dados_academicos_candidato->tipo_curso_graduacao = $academico_candidato->tipo_curso_graduacao;
            $novo_dados_academicos_candidato->instituicao_graduacao = $academico_candidato->instituicao_graduacao;
            $novo_dados_academicos_candidato->ano_conclusao_graduacao = $academico_candidato->ano_conclusao_graduacao;
            $novo_dados_academicos_candidato->curso_pos = $academico_candidato->curso_pos;
            if ($academico_candidato->tipo_curso_pos == 0) {
                $novo_dados_academicos_candidato->tipo_curso_pos = 9;
            }else{
                $novo_dados_academicos_candidato->tipo_curso_pos = $academico_candidato->tipo_curso_pos;
            }
            $novo_dados_academicos_candidato->instituicao_pos = $academico_candidato->instituicao_pos;
            $novo_dados_academicos_candidato->ano_conclusao_pos = $academico_candidato->ano_conclusao_pos;
            $novo_dados_academicos_candidato->created_at = $academico_candidato->created_at;
            $novo_dados_academicos_candidato->updated_at = $academico_candidato->updated_at;
            $novo_dados_academicos_candidato->save();

        }

        $tableToCheck = 'dados_academicos_candidato';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // //Fim da migração dos dados acadêmicos do candidato


        //Início da migração dos dados pessoais do candidato
            $users = DB::connection('pos2')->table('dados_pessoais')->where('id_user','>',2)->orderBy('id_user', 'asc')->get();

            foreach ($users as $user) {
                
                $novo_dado_pessoal_candidato = new DadoPessoalCandidato();

                $novo_dado_pessoal_candidato->id_candidato = $user->id_user;
                $novo_dado_pessoal_candidato->data_nascimento = $user->data_nascimento;
                $novo_dado_pessoal_candidato->numerorg = $user->numerorg;
                $novo_dado_pessoal_candidato->endereco = $user->endereco;
                $novo_dado_pessoal_candidato->cep = $user->cep;
                $novo_dado_pessoal_candidato->pais = $user->pais;
                $novo_dado_pessoal_candidato->estado = $user->estado;
                $novo_dado_pessoal_candidato->cidade = $user->cidade;
                $novo_dado_pessoal_candidato->celular = $user->celular;
                $novo_dado_pessoal_candidato->created_at = $user->created_at;
                $novo_dado_pessoal_candidato->updated_at = $user->updated_at;
                $novo_dado_pessoal_candidato->save();

            }

            $tableToCheck = 'dados_pessoais_candidato';

            $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
            $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

            DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        //Fim da migração dos dados pessoais do candidato
        
        // //Início da migração dos dados pessoais do recomendante
        
        $users_rec = DB::connection('pos2')->table('dados_recomendantes')->orderBy('id', 'asc')->get();

        foreach ($users_rec as $reco) {

            $novo_dado_recomendante = new DadoPessoalRecomendante();

            $novo_dado_recomendante->id_recomendante = $reco->id_prof;
            $novo_dado_recomendante->instituicao_recomendante = $reco->instituicao_recomendante;
            $novo_dado_recomendante->titulacao_recomendante = $reco->titulacao_recomendante;
            $novo_dado_recomendante->area_recomendante = $reco->area_recomendante;
            $novo_dado_recomendante->ano_titulacao = $reco->ano_titulacao;
            $novo_dado_recomendante->inst_obtencao_titulo = $reco->inst_obtencao_titulo;
            $novo_dado_recomendante->endereco_recomendante = $reco->endereco_recomendante;
            $novo_dado_recomendante->atualizado = $reco->atualizado;
            $novo_dado_recomendante->created_at = $reco->created_at;
            $novo_dado_recomendante->updated_at = $reco->updated_at;
            $novo_dado_recomendante->save();

            $atualiza_usuario = User::find($reco->id_prof);

            $nome_recomendante['nome'] = $reco->nome_recomendante;

            $atualiza_usuario->update($nome_recomendante);

        }

        $tableToCheck = 'dados_pessoais_recomendantes';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // //Fim da migração dos dados pessoais do recomendante
        

        // //Início da migração das cartas de recomendação
        
        $cartas_recomendacao = DB::connection('pos2')->table('cartas_recomendacoes')->orderBy('id', 'asc')->get();

        foreach ($cartas_recomendacao as $carta) {
            
            $nova_carta_recomendacao = new CartaRecomendacao();

            $nova_carta_recomendacao->id_recomendante = $carta->id_prof;
            $nova_carta_recomendacao->id_candidato = $carta->id_aluno;
            $nova_carta_recomendacao->programa_pretendido = $carta->programa_pretendido;
            $nova_carta_recomendacao->id_inscricao_pos = $carta->id_inscricao_pos;
            $nova_carta_recomendacao->tempo_conhece_candidato = $carta->tempo_conhece_candidato;
            $nova_carta_recomendacao->circunstancia_1 = $carta->circunstancia_1;
            $nova_carta_recomendacao->circunstancia_2 = $carta->circunstancia_2;
            $nova_carta_recomendacao->circunstancia_3 = $carta->circunstancia_3;
            $nova_carta_recomendacao->circunstancia_4 = $carta->circunstancia_4;
            $nova_carta_recomendacao->circunstancia_outra = $carta->circunstancia_outra;
            $nova_carta_recomendacao->desempenho_academico = $carta->desempenho_academico;
            $nova_carta_recomendacao->capacidade_aprender = $carta->capacidade_aprender;
            $nova_carta_recomendacao->capacidade_trabalhar = $carta->capacidade_trabalhar;
            $nova_carta_recomendacao->criatividade = $carta->criatividade;
            $nova_carta_recomendacao->curiosidade = $carta->curiosidade;
            $nova_carta_recomendacao->esforco = $carta->esforco;
            $nova_carta_recomendacao->expressao_escrita = $carta->expressao_escrita;
            $nova_carta_recomendacao->expressao_oral = $carta->expressao_oral;
            $nova_carta_recomendacao->relacionamento = $carta->relacionamento;
            $nova_carta_recomendacao->antecedentes_academicos = $carta->antecedentes_academicos;
            $nova_carta_recomendacao->possivel_aproveitamento = $carta->possivel_aproveitamento;
            $nova_carta_recomendacao->informacoes_relevantes = $carta->informacoes_relevantes;
            $nova_carta_recomendacao->como_aluno = $carta->como_aluno;
            $nova_carta_recomendacao->como_orientando = $carta->como_orientando;
            $nova_carta_recomendacao->completada = $carta->completada;
            $nova_carta_recomendacao->created_at = $carta->created_at;
            $nova_carta_recomendacao->updated_at = $carta->updated_at;
            $nova_carta_recomendacao->save();
        }

        $tableToCheck = 'cartas_recomendacoes';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');

        // //Fim da migração das cartas de recomendação
        
        // //Início da migração das finalizações das inscrições
        
        $finaliza_insc = DB::connection('pos2')->table('finaliza_inscricao')->orderBy('id', 'asc')->get();

        foreach ($finaliza_insc as $final) {
            
            $novo_finaliza_inscricao = new FinalizaInscricao();

            $novo_finaliza_inscricao->id_candidato = $final->id_user;
            $novo_finaliza_inscricao->id_inscricao_pos = $final->id_inscricao_pos;
            $novo_finaliza_inscricao->finalizada = $final->finalizada;
            $novo_finaliza_inscricao->created_at = $final->created_at;
            $novo_finaliza_inscricao->updated_at = $final->updated_at;
            $novo_finaliza_inscricao->save();
        }

        $tableToCheck = 'finaliza_inscricao';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        
        // //Fim da migração das finalizações das inscrições
        
        // //Início da migração das escolhas do candidato
        
        $escolhas_candidato = DB::connection('pos2')->table('escolhas_candidato')->orderBy('id', 'asc')->get();

        foreach ($escolhas_candidato as $escolha) {
            
            $nova_escolha_candidatos = new EscolhaCandidato();

            $nova_escolha_candidatos->id_candidato = $escolha->id_user;
            $nova_escolha_candidatos->programa_pretendido = $escolha->programa_pretendido;
            if ($escolha->area_pos == 0) {
                $nova_escolha_candidatos->area_pos = 10;
            }else{
                $nova_escolha_candidatos->area_pos = $escolha->area_pos;    
            }
            $nova_escolha_candidatos->interesse_bolsa = $escolha->interesse_bolsa;
            $nova_escolha_candidatos->vinculo_empregaticio = $escolha->vinculo_empregaticio;
            $nova_escolha_candidatos->id_inscricao_pos = $escolha->id_inscricao_pos;
            $nova_escolha_candidatos->created_at = $escolha->created_at;
            $nova_escolha_candidatos->updated_at = $escolha->updated_at;
            $nova_escolha_candidatos->save();

        }

        $tableToCheck = 'escolhas_candidato';

        $highestId = DB::table($tableToCheck)->select(DB::raw('MAX(id)'))->first();
        $nextId = DB::table($tableToCheck)->select(DB::raw('nextval(\''.$tableToCheck.'_id_seq\')'))->first();

        DB::select('SELECT setval(\''.$tableToCheck.'_id_seq\', '.$highestId->max.')');
        // //Fim da migração das escolhas do candidato
        
  }
}