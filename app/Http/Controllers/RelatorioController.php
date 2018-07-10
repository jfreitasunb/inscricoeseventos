<?php

namespace InscricoesEventosMat\Http\Controllers;

use Auth;
use DB;
use Mail;
use Session;
use File;
use ZipArchive;
use PDF;
use Imagick;
use InscricoesEventosMat\Http\Controllers\FPDFController;
use Carbon\Carbon;
use InscricoesEventosMat\Models\OfertaCursoVerao;
use InscricoesEventosMat\Models\User;
use InscricoesEventosMat\Models\ConfiguraInscricaoPos;
use InscricoesEventosMat\Models\CursoVeraoMat;
use InscricoesEventosMat\Models\FinalizaInscricao;
use InscricoesEventosMat\Models\DadoPessoalCandidato;
use InscricoesEventosMat\Models\Paises;
use InscricoesEventosMat\Models\Formacao;
use InscricoesEventosMat\Models\Estado;
use InscricoesEventosMat\Models\Cidade;
use InscricoesEventosMat\Models\DadoAcademicoCandidato;
use InscricoesEventosMat\Models\Documento;
use InscricoesEventosMat\Models\EscolhaCursoVerao;
use InscricoesEventosMat\Models\ProgramaPos;
use Illuminate\Http\Request;
use InscricoesEventosMat\Mail\EmailVerification;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Http\Controllers\AuthController;
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

  public function ContaInscricoes($id_inscricao_verao, $programa)
  {
     
    return DB::table('escolhas_curso_verao')->where('escolhas_curso_verao.id_inscricao_verao', $id_inscricao_verao)->where('escolhas_curso_verao.curso_verao', $programa)->join('finaliza_inscricao', 'finaliza_inscricao.id_candidato', 'escolhas_curso_verao.id_candidato')->where('finaliza_inscricao.finalizada', true)->where('finaliza_inscricao.id_inscricao_verao', $id_inscricao_verao)->count();

  }


  public function ConsolidaCabecalhoCSV()
  {

    return $cabecalho = ["Nome","E-mail","Programa Pretendido"];
  }

  public function ConsolidaLocaisArquivos($edital)
  {

    $locais_arquivos = [];
    
    $locais_arquivos['arquivos_temporarios'] = storage_path("app/public/relatorios/temporario/");

    $locais_arquivos['ficha_inscricao'] = storage_path("app/public/relatorios/ficha_inscricao/");

    $locais_arquivos['local_relatorios'] = storage_path("app/public/relatorios/edital_".$edital."/");
    
    $locais_arquivos['arquivo_relatorio_csv'] = 'Inscricoes_Edital_'.$edital.'.csv';

    $locais_arquivos['local_documentos'] = storage_path('app/');

    $locais_arquivos['arquivo_zip'] = $locais_arquivos['local_relatorios'].'zip/';

    File::isDirectory($locais_arquivos['arquivos_temporarios']) or File::makeDirectory($locais_arquivos['arquivos_temporarios'],0775,true);

    File::isDirectory($locais_arquivos['ficha_inscricao']) or File::makeDirectory($locais_arquivos['ficha_inscricao'],0775,true);

    File::isDirectory($locais_arquivos['local_relatorios']) or File::makeDirectory($locais_arquivos['local_relatorios'],0775,true);

    File::isDirectory($locais_arquivos['arquivo_zip']) or File::makeDirectory($locais_arquivos['arquivo_zip'],0775,true);

    return $locais_arquivos;
  }


  public function ConsolidaDadosPessoais($id_candidato)
  {
    $consolida_dados = [];

    $dado_pessoal = new DadoPessoalCandidato();

    $dados_pessoais_candidato = $dado_pessoal->retorna_dados_pessoais($id_candidato);

    $paises = new Paises();

    $estado = new Estado();

    $cidade = new Cidade();

    $data_hoje = (new Carbon())->format('Y-m-d');

    $consolida_dados['nome'] = $dados_pessoais_candidato->nome;

    $consolida_dados['email'] = $dados_pessoais_candidato->email;

    $consolida_dados['data_nascimento'] = Carbon::createFromFormat('Y-m-d', $dados_pessoais_candidato->data_nascimento)->format('d/m/Y');

    $array_data_hoje = explode('-', $data_hoje);
    
    $idade = Carbon::parse($dados_pessoais_candidato->data_nascimento)->diff(Carbon::now())->format('%y');

    $consolida_dados['idade'] = $idade;
    
    $consolida_dados['numerorg'] = $dados_pessoais_candidato->numerorg;

    $consolida_dados['endereco'] = $dados_pessoais_candidato->endereco;

    $consolida_dados['cep'] = $dados_pessoais_candidato->cep;

    if (!is_null($dados_pessoais_candidato->pais)) {
      $consolida_dados['nome_pais'] = $paises->retorna_nome_pais_por_id($dados_pessoais_candidato->pais);
    }else{
      $consolida_dados['nome_pais'] = null;
    }

    if (!is_null($dados_pessoais_candidato->estado)) {
      if (!is_null($estado->retorna_nome_estados_por_id($dados_pessoais_candidato->pais, $dados_pessoais_candidato->estado))) {
        $consolida_dados['nome_estado'] = $estado->retorna_nome_estados_por_id($dados_pessoais_candidato->pais, $dados_pessoais_candidato->estado);
      }else{
        $consolida_dados['nome_estado'] = null;
      }
    }else{
      $consolida_dados['nome_estado'] = null;
    }

    if (!is_null($dados_pessoais_candidato->cidade)) {
      $consolida_dados['nome_cidade'] = $cidade->retorna_nome_cidade_por_id($dados_pessoais_candidato->cidade, $dados_pessoais_candidato->estado);
    }else{
      $consolida_dados['nome_cidade'] = null;
    }

    $consolida_dados['celular'] = $dados_pessoais_candidato->celular;

    return $consolida_dados;
  }

  public function ConsolidaDadosAcademicos($id_candidato, $locale_relatorio)
  {

    $consolida_academico = [];

    $dado_academico = new DadoAcademicoCandidato();

    $formacao = new Formacao();

    $dados_academicos_candidato = $dado_academico->retorna_dados_academicos($id_candidato);

    $consolida_academico['curso_graduacao'] = $dados_academicos_candidato->curso_graduacao;
    $consolida_academico['tipo_curso_graduacao'] = $formacao->pega_tipo_formacao($dados_academicos_candidato->tipo_curso_graduacao,'Graduação', $locale_relatorio);
    $consolida_academico['instituicao_graduacao'] = $dados_academicos_candidato->instituicao_graduacao;
    $consolida_academico['ano_conclusao_graduacao'] = $dados_academicos_candidato->ano_conclusao_graduacao;
    $consolida_academico['curso_pos'] = $dados_academicos_candidato->curso_pos;
    $consolida_academico['tipo_curso_pos'] = $formacao->pega_tipo_formacao($dados_academicos_candidato->tipo_curso_pos,'Pós-Graduação', $locale_relatorio);
    $consolida_academico['instituicao_pos'] = $dados_academicos_candidato->instituicao_pos;
    $consolida_academico['ano_conclusao_pos'] = $dados_academicos_candidato->ano_conclusao_pos;

    return $consolida_academico;
  }

  public function ConsolidaEscolhaCandidato($id_candidato,$id_inscricao_verao, $locale_relatorio)
  {
    $consolida_escolha = [];

    $escolha_candidato = new EscolhaCursoVerao();

    $curso_verao = new CursoVeraoMat();

    $programa_pos = new ProgramaPos();

    $oferta = new OfertaCursoVerao();

    $escolha_feita_candidato = $escolha_candidato->retorna_escolha_candidato($id_candidato,$id_inscricao_verao);

    $consolida_escolha['curso_verao'] = null;

    $consolida_escolha['programa_pretendido'] = $programa_pos->pega_programa_pos_mat($escolha_candidato->retorna_escolha_programa($id_candidato,$id_inscricao_verao)->programa_pretendido, $locale_relatorio);
    foreach ($escolha_feita_candidato as $escolha) {

      if ($oferta->retorna_seleciona_pos($id_inscricao_verao, $escolha->curso_verao)) {
        $selecao = " (Seleção para a Pós)";
      }else{
        $selecao = "";
      }

      $consolida_escolha['curso_verao'] .= $curso_verao->pega_area_pos_mat((int)$escolha->curso_verao, $locale_relatorio).$selecao."_";
    }

    return $consolida_escolha;
  }

  public function ConsolidaNomeArquivos($local_arquivos_temporarios, $local_arquivos_definitivos, $dados_candidato_para_relatorio)
  {
    $nome_arquivos = [];
    
    $nome_arquivos['arquivo_relatorio_candidato_temporario'] = $local_arquivos_temporarios.str_replace('\'s','',str_replace(' ', '-', strtr($dados_candidato_para_relatorio['programa_pretendido'], $this->normalizeChars))).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['nome'], $this->normalizeChars)).'_'.$dados_candidato_para_relatorio['id_candidato'].'.pdf';
    
    $nome_arquivos['arquivo_relatorio_candidato_final'] = $local_arquivos_definitivos.'Inscricao_'.str_replace('\'s','',str_replace(' ', '-', strtr($dados_candidato_para_relatorio['programa_pretendido'], $this->normalizeChars))).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['nome'], $this->normalizeChars)).'_'.$dados_candidato_para_relatorio['id_candidato'].'.pdf';

    return $nome_arquivos;
  }

  public function ConsolidaDocumentosPDF($id_candidato, $local_documentos, $id_inscricao_verao)
  {

    $nome_uploads = [];

    $documento = new Documento();
    
    // $nome_documento_banco = $local_documentos.$documento->retorna_documento($id_candidato, $id_inscricao_verao)->nome_arquivo;

    $nome_historico_banco = $local_documentos.$documento->retorna_historico($id_candidato, $id_inscricao_verao)->nome_arquivo;

    // if (File::extension($nome_documento_banco) != 'pdf')
    //   {

        
    //     $nome_documento_pdf = str_replace(File::extension($nome_documento_banco),'pdf', $nome_documento_banco);

    //     DB::table('arquivos_enviados')->where('nome_arquivo', $nome_documento_banco)->where('tipo_arquivo', 'Documentos')->where('id_inscricao_verao', $id_inscricao_verao)->update(['nome_arquivo' => $nome_documento_pdf]);

    //     $img = new Imagick($nome_documento_banco);
    //     $img->setImageFormat('pdf');
    //     $success = $img->writeImage($nome_documento_pdf);
    //   }

      if (File::extension($nome_historico_banco) != 'pdf')
      {

        $nome_historico_pdf = str_replace(File::extension($nome_historico_banco),'pdf', $nome_historico_banco);
        
        DB::table('arquivos_enviados')->where('nome_arquivo', $nome_historico_banco)->where('tipo_arquivo', 'Histórico')->where('id_inscricao_verao', $id_inscricao_verao)->update(['nome_arquivo' => $nome_historico_pdf]);

        $img = new Imagick($nome_historico_banco);
        $img->setImageFormat('pdf');
        $success = $img->writeImage($nome_historico_pdf);
      }

    // $nome_uploads['documento_pdf'] = str_replace(File::extension($nome_documento_banco),'pdf', $nome_documento_banco);
    $nome_uploads['historico_pdf'] = str_replace(File::extension($nome_historico_banco),'pdf', $nome_historico_banco);

    return $nome_uploads;
  }

  public function ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads)
  {
    $process = new Process('pdftk '.$nome_arquivos['arquivo_relatorio_candidato_temporario'].' '.$nome_uploads['historico_pdf'].' cat output '.$nome_arquivos['arquivo_relatorio_candidato_final']);

    $process->setTimeout(3600);
    
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

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

    $locale_relatorio = 'pt-br';

    $relatorio = new ConfiguraInscricaoPos();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

    $oferta_verao = new OfertaCursoVerao();

    $cursos_ofertados = $oferta_verao->retorna_cursos_ofertados($relatorio_disponivel->id_inscricao_verao, $locale_relatorio);

    foreach ($cursos_ofertados as $curso) {
      
      $contagem[$curso->id_curso_verao] = $this->ContaInscricoes($relatorio_disponivel->id_inscricao_verao, $curso->id_curso_verao);

    }

    $total_inscritos = array_sum($contagem);

    // $nome_programas = implode('/', $programa_para_inscricao);

    $arquivos_zipados_para_view = "";

    $documentos_zipados = "";

    $relatorio_csv = "";

    $monitoria = "";

    return view('templates.partials.coordenador.relatorio_pos_edital_vigente')->with(compact('monitoria','relatorio_disponivel', 'cursos_ofertados', 'total_inscritos', 'contagem', 'arquivos_zipados_para_view','relatorio_csv'));
  }

   public function getListaRelatoriosAnteriores()
  {

    $relatorio = new ConfiguraInscricaoPos();

    $relatorios_anteriores = $relatorio->retorna_lista_para_relatorio();

    $arquivos_zipados_para_view = "";

    $documentos_zipados = "";

    $relatorio_csv = "";

    $monitoria = "";

    return view('templates.partials.coordenador.relatorio_pos_editais_anteriores')->with(compact('monitoria','relatorios_anteriores', 'arquivos_zipados_para_view','relatorio_csv'));
 }


  public function getArquivosRelatorios($id_inscricao_verao,$arquivos_zipados_para_view,$relatorio_csv)
  {

    $locale_relatorio = 'pt-br';

  $relatorio = new ConfiguraInscricaoPos();

  $relatorio_disponivel = $relatorio->retorna_edital_vigente();

  $programas_disponiveis = explode("_", $relatorio->retorna_inscricao_ativa()->tipo_evento);

  $oferta_verao = new OfertaCursoVerao();

    $cursos_ofertados = $oferta_verao->retorna_cursos_ofertados($relatorio_disponivel->id_inscricao_verao, $locale_relatorio);

    foreach ($cursos_ofertados as $curso) {
      
      $contagem[$curso->id_curso_verao] = $this->ContaInscricoes($relatorio_disponivel->id_inscricao_verao, $curso->id_curso_verao);

    }

  $nome_programa_pos = new ProgramaPos();

    foreach ($programas_disponiveis as $programa) {
     
     $programa_para_inscricao[$programa] = $nome_programa_pos->pega_programa_pos_mat($programa, $locale_relatorio);
     
     $contagem[$programa_para_inscricao[$programa]] = $this->ContaInscricoes($relatorio_disponivel->id_inscricao_verao, $programa);
    }

  $total_inscritos = array_sum($contagem);
  
  $nome_programas = implode('/', $programa_para_inscricao);

  $monitoria = $id_inscricao_verao;

  $local_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

  $endereco_zip_mudar = '/var/www/inscricoesverao/storage/app/public/';

  //Para ser usado no MAT
  // $endereco_zip_mudar = '/var/www/inscricoesverao/storage/app/public/';

  $local_arquivos['local_relatorios'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['local_relatorios']);

  $local_arquivos['arquivo_zip'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['arquivo_zip']);

  return view('templates.partials.coordenador.relatorio_pos_edital_vigente')->with(compact('monitoria','contagem', 'total_inscritos', 'cursos_ofertados', 'relatorio_disponivel','arquivos_zipados_para_view','relatorio_csv','local_arquivos'));

  }


  public function geraRelatorio($id_inscricao_verao)
  {

    $locale_relatorio = 'pt-br';

    $relatorio = ConfiguraInscricaoPos::find($id_inscricao_verao);

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio->ano_evento);

    $relatorio_csv = Writer::createFromPath($locais_arquivos['local_relatorios'].$locais_arquivos['arquivo_relatorio_csv'], 'w+');

    $relatorio_csv->insertOne($this->ConsolidaCabecalhoCSV());


    $finaliza = new FinalizaInscricao();
    $usuarios_finalizados = $finaliza->retorna_usuarios_relatorios($id_inscricao_verao);

    foreach ($usuarios_finalizados as $candidato) {

      $linha_arquivo = [];

      $dados_candidato_para_relatorio = [];

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio->ano_evento;

      $dados_candidato_para_relatorio['id_candidato'] = $candidato->id_candidato;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_candidato']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];

      $linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_candidato'])->email;

      foreach ($this->ConsolidaDadosAcademicos($dados_candidato_para_relatorio['id_candidato'], $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_candidato'], $id_inscricao_verao, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['programa_pretendido'] = $dados_candidato_para_relatorio['programa_pretendido'];

      $nome_arquivos = [];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);

      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $nome_uploads = $this->ConsolidaDocumentosPDF($dados_candidato_para_relatorio['id_candidato'], $locais_arquivos['local_documentos'], $id_inscricao_verao);

      $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($relatorio->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio->tipo_evento);

    return $this->getArquivosRelatorios($id_inscricao_verao,$arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }

  public function geraFichaIndividual($id_candidato, $locale_relatorio)
  {

      $relatorio = new ConfiguraInscricaoPos();

      $relatorio_disponivel = $relatorio->retorna_edital_vigente();

      $id_inscricao_verao = $relatorio_disponivel->id_inscricao_verao;

      $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio_disponivel->ano_evento;

      $dados_candidato_para_relatorio['id_candidato'] = $id_candidato;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_candidato']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      foreach ($this->ConsolidaDadosAcademicos($dados_candidato_para_relatorio['id_candidato'], $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_candidato'], $id_inscricao_verao, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);
      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $nome_uploads = $this->ConsolidaDocumentosPDF($dados_candidato_para_relatorio['id_candidato'], $locais_arquivos['local_documentos'], $id_inscricao_verao);

      $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

      $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';

      //Para ser usado no MAT
      // $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';
      
      return str_replace($endereco_mudar, 'storage/', $nome_arquivos['arquivo_relatorio_candidato_final']);
  }

  public function getArquivosRelatoriosAnteriores($id_inscricao_verao,$arquivos_zipados_para_view,$relatorio_csv)
  {

    $relatorio = new ConfiguraInscricaoPos();

    $relatorios_anteriores = $relatorio->retorna_lista_para_relatorio();

    $monitoria = $id_inscricao_verao;

    return redirect()->back()->with(compact('monitoria','relatorios_anteriores','arquivos_zipados_para_view','relatorio_csv'));
  }


  public function geraRelatoriosAnteriores($id_inscricao_verao)
  {

    $locale_relatorio = 'pt-br';

    $relatorio_disponivel = ConfiguraInscricaoPos::find($id_inscricao_verao);

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel['ano_evento']);

    $relatorio_csv = Writer::createFromPath($locais_arquivos['local_relatorios'].$locais_arquivos['arquivo_relatorio_csv'], 'w+');

    $relatorio_csv->insertOne($this->ConsolidaCabecalhoCSV());


    $finaliza = new FinalizaInscricao();
    $usuarios_finalizados = $finaliza->retorna_usuarios_relatorios($id_inscricao_verao);


    foreach ($usuarios_finalizados as $candidato) {

      $linha_arquivo = [];

      $dados_candidato_para_relatorio = [];

      $dados_candidato_para_relatorio['ano_evento'] = $relatorio_disponivel->ano_evento;

      $dados_candidato_para_relatorio['id_candidato'] = $candidato->id_candidato;

      foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_candidato']) as $key => $value) {
         $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['nome'] = $dados_candidato_para_relatorio['nome'];

      $linha_arquivo['email'] = User::find($dados_candidato_para_relatorio['id_candidato'])->email;

      foreach ($this->ConsolidaDadosAcademicos($dados_candidato_para_relatorio['id_candidato'], $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_candidato'], $id_inscricao_verao, $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
      }

      $linha_arquivo['programa_pretendido'] = $dados_candidato_para_relatorio['programa_pretendido'];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);
      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

      $nome_uploads = $this->ConsolidaDocumentosPDF($dados_candidato_para_relatorio['id_candidato'], $locais_arquivos['local_documentos'], $id_inscricao_verao);

      $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($relatorio_disponivel->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio_disponivel->tipo_evento);

    return $this->getArquivosRelatoriosAnteriores($id_inscricao_verao,$arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }


  public function getRelatorioPos()
  {

    return view('templates.partials.coordenador.relatorio_pos_edital_vigente');
  }

  public function geraFichaInscricao($id_candidato, $id_inscricao_verao, $locale_relatorio)
  {

    $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';

    //Para ser usado no MAT
    // $endereco_mudar = '/var/www/inscricoesverao/storage/app/public/';
    
    $relatorio = new ConfiguraInscricaoPos();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();

    $locais_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

    $dados_candidato_para_relatorio = [];

    $dados_candidato_para_relatorio['ano_evento'] = $relatorio_disponivel->ano_evento;

    $dados_candidato_para_relatorio['id_candidato'] = $id_candidato;

    foreach ($this->ConsolidaDadosPessoais($dados_candidato_para_relatorio['id_candidato']) as $key => $value) {
       $dados_candidato_para_relatorio[$key] = $value;
    }

    foreach ($this->ConsolidaDadosAcademicos($dados_candidato_para_relatorio['id_candidato'], $locale_relatorio) as $key => $value) {
        $dados_candidato_para_relatorio[$key] = $value;
    }

    foreach ($this->ConsolidaEscolhaCandidato($dados_candidato_para_relatorio['id_candidato'], $id_inscricao_verao, $locale_relatorio) as $key => $value) {
      $dados_candidato_para_relatorio[$key] = $value;
    }

    $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['ficha_inscricao'], $dados_candidato_para_relatorio);
    
    $pdf = PDF::loadView('templates.partials.candidato.pdf_ficha_inscricao', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
    $pdf->save($nome_arquivos['arquivo_relatorio_candidato_temporario']);

    $nome_uploads = $this->ConsolidaDocumentosPDF($dados_candidato_para_relatorio['id_candidato'], $locais_arquivos['local_documentos'], $id_inscricao_verao);

    $this->ConsolidaFichaRelatorio($nome_arquivos, $nome_uploads);

    return str_replace($endereco_mudar,'storage/', $nome_arquivos['arquivo_relatorio_candidato_final']);
  }

}