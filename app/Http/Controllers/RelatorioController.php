<?php

namespace InscricoesEventos\Http\Controllers;

use Auth;
use DB;
use Mail;
use Session;
use File;
use ZipArchive;
use PDF;
use Imagick;
use InscricoesEventos\Http\Controllers\FPDFController;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\DadoPessoalParticipante;
use InscricoesEventos\Models\TrabalhoSubmetido;
use InscricoesEventos\Models\Paises;
use InscricoesEventos\Models\TipoParticipacao;
use InscricoesEventos\Models\ConfiguraCategoriaParticipante;
use InscricoesEventos\Models\ConfiguraTipoApresentacao;
use InscricoesEventos\Models\TipoCoordenador;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\LatexTemplateController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
* Classe para visualização da página inicial.
*/
class RelatorioController extends BaseController
{

  protected $normalizeChars = array(
      'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
      'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
      'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
      'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
      'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
      'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
      'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
      'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
    );

  public function ContaInscricoes($id_inscricao_evento, $programa)
  {
     
    return DB::table('escolhas_curso_verao')->where('escolhas_curso_verao.id_inscricao_evento', $id_inscricao_evento)->where('escolhas_curso_verao.curso_verao', $programa)->join('finaliza_inscricao', 'finaliza_inscricao.id_participante', 'escolhas_curso_verao.id_participante')->where('finaliza_inscricao.finalizada', true)->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->count();

  }

  public function ConsolidaCabecalhoCSV()
  {

    return $cabecalho = ["Nome","E-mail","Programa Pretendido"];
  }

  public function ConsolidaLocaisArquivos($evento)
  {

    $locais_arquivos = [];

    $locais_arquivos['ficha_inscricao'] = storage_path("app/public/relatorios/ficha_inscricao/");

    $locais_arquivos['local_relatorios'] = storage_path("app/public/relatorios/evento_".$evento."/");
    
    $locais_arquivos['arquivo_relatorio_csv'] = 'Inscricoes_Evento_'.$evento.'.csv';

    $locais_arquivos['arquivo_zip'] = $locais_arquivos['local_relatorios'].'zip/';


    File::isDirectory($locais_arquivos['ficha_inscricao']) or File::makeDirectory($locais_arquivos['ficha_inscricao'],0775,true);

    File::isDirectory($locais_arquivos['local_relatorios']) or File::makeDirectory($locais_arquivos['local_relatorios'],0775,true);

    File::isDirectory($locais_arquivos['arquivo_zip']) or File::makeDirectory($locais_arquivos['arquivo_zip'],0775,true);

    return $locais_arquivos;
  }


  public function ConsolidaDadosPessoais($id_participante)
  {
    $consolida_dados = [];

    $dado_pessoal = new DadoPessoalParticipante();

    $dados_pessoais_candidato = $dado_pessoal->retorna_dados_pessoais($id_participante);

    $paises = new Paises();

    $data_hoje = (new Carbon())->format('Y-m-d');

    $consolida_dados['nome'] = $dados_pessoais_candidato->nome;

    $consolida_dados['email'] = $dados_pessoais_candidato->email;

    $consolida_dados['nome_cracha'] = $dados_pessoais_candidato->nome_cracha;

    $consolida_dados['numero_documento'] = $dados_pessoais_candidato->numero_documento;

    $consolida_dados['instituicao'] = $dados_pessoais_candidato->instituicao;

    if (!is_null($dados_pessoais_candidato->id_pais)) {
      $consolida_dados['nome_pais'] = $paises->retorna_nome_pais_por_id($dados_pessoais_candidato->id_pais);
    }else{
      $consolida_dados['nome_pais'] = null;
    }

    return $consolida_dados;
  }

  public function ConsolidaEscolhaCandidato($id_participante, $id_inscricao_evento, $locale_participante)
  {
    $consolida_escolha = [];

    $tipo_participacao = new TipoParticipacao();

    $categoria_participacao = new ConfiguraCategoriaParticipante();

    $tipo_apresentacao = new ConfiguraTipoApresentacao();


    $escolha_participacao = $tipo_participacao->retorna_participacao($id_inscricao_evento, $id_participante);

    $consolida_escolha['categoria_participante'] = $categoria_participacao->retorna_nome_categoria_por_id($escolha_participacao->id_categoria_participante, $locale_participante);

    $consolida_escolha['apresentar_trabalho'] = $escolha_participacao->apresentar_trabalho;


    $consolida_escolha['tipo_apresentacao'] = $tipo_apresentacao->retorna_nome_tipo_participacao_por_id($escolha_participacao->id_tipo_apresentacao, $locale_participante);

    return $consolida_escolha;
  }

  public function ConsolidaNomeArquivos($local_arquivos_definitivos, $dados_candidato_para_relatorio)
  {
    $nome_arquivos = [];
    
    $nome_arquivos['arquivo_relatorio_participante'] = $local_arquivos_definitivos.'Inscricao_'.str_replace('\'s','',str_replace(' ', '-', strtr($dados_candidato_para_relatorio['tipo_apresentacao'], $this->normalizeChars))).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['nome'], $this->normalizeChars)).'_'.$dados_candidato_para_relatorio['id_participante'].'.pdf';

    return $nome_arquivos;
  }

  public function ConsolidaArquivosZIP($edital, $arquivo_zip, $local_relatorios, $programas)
  {
    $locale_relatorio = 'pt-br';

    $nome_programa_pos = new ProgramaPos();

    $programa_para_relatorio = strtr($nome_programa_pos->pega_programa_pos_mat($programas, $locale_relatorio), $this->normalizeChars);
    
    $inscricoes_zipadas = 'Inscricoes_'.$programa_para_relatorio.'.zip';
    $arquivos_zipados_para_view[$programas] = $inscricoes_zipadas;

    $zip = new ZipArchive;

    if ( $zip->open( $arquivo_zip.$inscricoes_zipadas, ZipArchive::CREATE ) === true ){

     foreach (glob( $local_relatorios.'Inscricao_'.$programa_para_relatorio.'*') as $fileName ){
        $file = basename( $fileName );
        $zip->addFile( $fileName, $file );

     }

     $zip->close();
    }
    
    return $arquivos_zipados_para_view;
  }

  public function getListaRelatorios()
  {

    $user = $this->SetUser();
    
    $id_coordenador = $user->id_user;

    $locale_relatorio = 'pt-br';

    $relatorio = new ConfiguraInscricaoEvento();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

    $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

    $coordenador = new TipoCoordenador();

    $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

    $inscritos = new FinalizaInscricao();

    $total_inscritos = $inscritos->retorna_total_inscritos($id_inscricao_evento, $nivel_coordenador);

    $arquivos_zipados_para_view = "";

    $documentos_zipados = "";

    $relatorio_csv = "";

    $monitoria = "";

    // return view('templates.partials.coordenador.relatorio_pos_edital_vigente')->with(compact('monitoria','relatorio_disponivel', 'cursos_ofertados', 'total_inscritos', 'contagem', 'arquivos_zipados_para_view','relatorio_csv'));
    
     return view('templates.partials.coordenador.relatorio_pos_edital_vigente')->with(compact('monitoria','relatorio_disponivel', 'cursos_ofertados', 'total_inscritos', 'contagem', 'arquivos_zipados_para_view','relatorio_csv'));
  }

   public function getListaRelatoriosAnteriores()
  {

    $relatorio = new ConfiguraInscricaoEvento();

    $relatorios_anteriores = $relatorio->retorna_lista_para_relatorio();

    $arquivos_zipados_para_view = "";

    $documentos_zipados = "";

    $relatorio_csv = "";

    $monitoria = "";

    return view('templates.partials.coordenador.relatorio_pos_editais_anteriores')->with(compact('monitoria','relatorios_anteriores', 'arquivos_zipados_para_view','relatorio_csv'));
 }


  public function getArquivosRelatorios($id_inscricao_evento,$arquivos_zipados_para_view,$relatorio_csv)
  {

    $locale_relatorio = 'pt-br';

    $relatorio = new ConfiguraInscricaoEvento();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

    $programas_disponiveis = explode("_", $relatorio->retorna_inscricao_ativa()->tipo_evento);

    $oferta_verao = new OfertaCursoVerao();

    $cursos_ofertados = $oferta_verao->retorna_cursos_ofertados($relatorio_disponivel->id_inscricao_evento, $locale_relatorio);

    foreach ($cursos_ofertados as $curso) {
      
      $contagem[$curso->id_curso_verao] = $this->ContaInscricoes($relatorio_disponivel->id_inscricao_evento, $curso->id_curso_verao);

    }

    $nome_programa_pos = new ProgramaPos();

    foreach ($programas_disponiveis as $programa) {
     
     $programa_para_inscricao[$programa] = $nome_programa_pos->pega_programa_pos_mat($programa, $locale_relatorio);
     
     $contagem[$programa_para_inscricao[$programa]] = $this->ContaInscricoes($relatorio_disponivel->id_inscricao_evento, $programa);
    }

    $total_inscritos = array_sum($contagem);
    
    $nome_programas = implode('/', $programa_para_inscricao);

    $monitoria = $id_inscricao_evento;

    $local_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

    $endereco_zip_mudar = '/var/www/inscricoesverao/storage/app/public/';

    //Para ser usado no MAT
    // $endereco_zip_mudar = '/var/www/inscricoesverao/storage/app/public/';

    $local_arquivos['local_relatorios'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['local_relatorios']);

    $local_arquivos['arquivo_zip'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['arquivo_zip']);

    return view('templates.partials.coordenador.relatorio_pos_edital_vigente')->with(compact('monitoria','contagem', 'total_inscritos', 'cursos_ofertados', 'relatorio_disponivel','arquivos_zipados_para_view','relatorio_csv','local_arquivos'));

  }


  public function geraRelatorio($id_inscricao_evento)
  {
    $user = $this->SetUser();
    
    $id_coordenador = $user->id_user;

    $coordenador = new TipoCoordenador();

    $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

    $locale_relatorio = 'pt-br';

    $relatorio = ConfiguraInscricaoEvento::find($id_inscricao_evento);

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio->ano_evento);

    $relatorio_csv = Writer::createFromPath($locais_arquivos['local_relatorios'].$locais_arquivos['arquivo_relatorio_csv'], 'w+');

    $relatorio_csv->insertOne($this->ConsolidaCabecalhoCSV());


    $finaliza = new FinalizaInscricao();

    $usuarios_finalizados = $finaliza->retorna_usuarios_relatorios($id_inscricao_evento, $nivel_coordenador);
    
    foreach ($usuarios_finalizados as $candidato) {

      $linha_arquivo = [];

      $dados_candidato_para_relatorio = [];

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio->ano_evento;

      $dados_candidato_para_relatorio['id_participante'] = $candidato->id_participante;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];

      $linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }
      // dd($dados_candidato_para_relatorio);
      $nome_arquivos = [];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);

      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($relatorio->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio->tipo_evento);

    return $this->getArquivosRelatorios($id_inscricao_evento,$arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }

  public function geraFichaIndividual($id_participante, $locale_relatorio)
  {

      $relatorio = new ConfiguraInscricaoEvento();

      $relatorio_disponivel = $relatorio->retorna_edital_vigente();

      $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

      $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio_disponivel->ano_evento;

      $dados_candidato_para_relatorio['id_participante'] = $id_participante;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);
      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';

      //Para ser usado no MAT
      // $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';
      
      return str_replace($endereco_mudar, 'storage/', $nome_arquivos['arquivo_relatorio_participante']);
  }

  public function getArquivosRelatoriosAnteriores($id_inscricao_evento,$arquivos_zipados_para_view,$relatorio_csv)
  {

    $relatorio = new ConfiguraInscricaoEvento();

    $relatorios_anteriores = $relatorio->retorna_lista_para_relatorio();

    $monitoria = $id_inscricao_evento;

    return redirect()->back()->with(compact('monitoria','relatorios_anteriores','arquivos_zipados_para_view','relatorio_csv'));
  }


  public function geraRelatoriosAnteriores($id_inscricao_evento)
  {

    $locale_relatorio = 'pt-br';

    $relatorio_disponivel = ConfiguraInscricaoEvento::find($id_inscricao_evento);

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel['ano_evento']);

    $relatorio_csv = Writer::createFromPath($locais_arquivos['local_relatorios'].$locais_arquivos['arquivo_relatorio_csv'], 'w+');

    $relatorio_csv->insertOne($this->ConsolidaCabecalhoCSV());


    $finaliza = new FinalizaInscricao();
    $usuarios_finalizados = $finaliza->retorna_usuarios_relatorios($id_inscricao_evento);


    foreach ($usuarios_finalizados as $candidato) {

      $linha_arquivo = [];

      $dados_candidato_para_relatorio = [];

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio_disponivel->ano_evento;

      $dados_candidato_para_relatorio['id_participante'] = $candidato->id_participante;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];

      $linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_participante'])->email;

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['programa_pretendido'] = $dados_candidato_para_relatorio['programa_pretendido'];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);
      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($relatorio_disponivel->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio_disponivel->tipo_evento);

    return $this->getArquivosRelatoriosAnteriores($id_inscricao_evento,$arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }


  public function getRelatorioPos()
  {

    return view('templates.partials.coordenador.relatorio_pos_edital_vigente');
  }

  public function geraFichaInscricao($id_participante, $id_inscricao_evento, $locale_participante)
  {

    $endereco_mudar = '/var/www/inscricoeseventos/storage/app/public';
    
    $relatorio = new ConfiguraInscricaoEvento();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

    $dados_candidato_para_relatorio = [];

    $dados_candidato_para_relatorio['nome_evento'] = $relatorio_disponivel->nome_evento;

    $dados_candidato_para_relatorio['id_participante'] = $id_participante; 

    foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_participante']) as $key => $value) {
       $dados_candidato_para_relatorio[$key] = $value;
    }

    foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $locale_participante) as $key => $value) {
      $dados_candidato_para_relatorio[$key] = $value;
    }

    $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['ficha_inscricao'], $dados_candidato_para_relatorio);
    
    $pdf = PDF::loadView('templates.partials.participante.pdf_ficha_inscricao', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
    $pdf->save($nome_arquivos['arquivo_relatorio_participante']);
    
    return str_replace($endereco_mudar,'/storage', $nome_arquivos['arquivo_relatorio_participante']);
  }

  public function geraAbstract($id_participante, $id_inscricao_evento)
  {

    $trabalho = new TrabalhoSubmetido();

    $trabalho_submetido = $trabalho->retorna_trabalho_submetido($id_participante, $id_inscricao_evento);

    $local_abstrac = storage_path("app/latex_templates/");

    $template_abstract = $local_abstrac.'template_abstract.tex';

    $dados_para_template['titulotrabalho'] = $trabalho_submetido->titulo_trabalho;

    $dados_para_template['autortrabalho'] = $trabalho_submetido->autor_trabalho;

    $dados_para_template['abstractrabalho'] = $trabalho_submetido->abstract_trabalho;

    return LatexTemplateController::download($dados_para_template, $template_abstract, 'submited_abstract.pdf');
    
  }

}