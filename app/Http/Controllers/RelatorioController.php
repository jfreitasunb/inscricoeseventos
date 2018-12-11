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
use InscricoesEventos\Models\AreaPosMat;
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
     
    return DB::table('trabalho_submetido')->where('trabalho_submetido.id_inscricao_evento', $id_inscricao_evento)->where('trabalho_submetido.id_area_trabalho', $programa)->join('finaliza_inscricao', 'finaliza_inscricao.id_participante', 'trabalho_submetido.id_participante')->where('finaliza_inscricao.finalizada', true)->where('finaliza_inscricao.id_inscricao_evento', $id_inscricao_evento)->count();

  }

  public function ConsolidaCamposCSV($escolha_cabecalho)
  {
    switch ($escolha_cabecalho) {
      case 'cracha':
        return $campos = ["nome", "instituicao", "nome_cracha"];
        break;

      case 'lista_trabalhos_submetidos':
        return $campos = ["nome","email", "categoria_participante", "area_trabalho", "tipo_apresentacao",  "titulo_trabalho"];
        break;

      case 'lista_trabalhos_aceitos':
        return $campos = ["nome","email", "categoria_participante", "area_trabalho", "tipo_apresentacao",  "titulo_trabalho"];
        break;
      
      default:
        return $campos = ["nome","email", "categoria_participante", "instituicao", "nome_cracha"];
        break;
    }
  }
  public function ConsolidaCabecalhoCSV($escolha_cabecalho)
  {

    switch ($escolha_cabecalho) {
      case 'cracha':
        return $cabecalho = ["Nome", "Instituição", "Nome para Crachá"];
        break;

      case 'lista_trabalhos_submetidos':
        return $cabecalho = ["Nome", "E-mail", "Categoria Participante", "Área do Trabalho", "Tipo de Apresentação",  "Título do Trabalho"];
        break;

      case 'lista_trabalhos_aceitos':
        return $cabecalho = ["Nome", "Instituição", "E-mail", "Categoria Participante", "Área do Trabalho", "Tipo de Apresentação",  "Título do Trabalho"];
        break;

      case 'caderno_de_resumos':
        break;
      
      default:
        return $cabecalho = ["Nome", "Instituição", "Nome para Crachá", "E-mail", "Deseja apresentar trabalho?","Categoria Participante", "Área do Trabalho", "Tipo de Apresentação",  "Título do Trabalho"];
        break;
    }
    
  }

  public function ConsolidaLocaisArquivos($evento)
  {

    $locais_arquivos = [];

    $locais_arquivos['arquivos_temporarios'] = storage_path("app/public/relatorios/evento_".$evento."/temporario/");
    
    $locais_arquivos['ficha_inscricao'] = storage_path("app/public/relatorios/ficha_inscricao/");

    $locais_arquivos['local_relatorios'] = storage_path("app/public/relatorios/evento_".$evento."/");
    
    $locais_arquivos['lista_participante'] = 'Inscricoes_Evento_'.$evento.'.csv';

    $locais_arquivos['cracha'] = 'Crachas_Evento_'.$evento.'.csv';

    $locais_arquivos['lista_trabalhos_submetidos'] = 'Trabalhos_Submetidos_Evento_'.$evento.'.csv';

    $locais_arquivos['lista_trabalhos_aceitos'] = 'Trabalhos_Aceitos_Evento_'.$evento.'.csv';

    $locais_arquivos['caderno_de_resumos'] = 'Caderno_de_Resumos_Evento_'.$evento.'.tex';

    $locais_arquivos['arquivo_zip'] = $locais_arquivos['local_relatorios'].'zip/';

    File::isDirectory($locais_arquivos['arquivos_temporarios']) or File::makeDirectory($locais_arquivos['arquivos_temporarios'],0775,true);

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

    if (!isset($dados_pessoais_candidato->nome)) {
      dd($id_participante);
    }

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

    if ($escolha_participacao->apresentar_trabalho) {
      $trabalho = new TrabalhoSubmetido();

      $id_area_trabalho = $trabalho->retorna_trabalho_submetido($id_participante, $id_inscricao_evento)->id_area_trabalho;

      $titulo_trabalho = $trabalho->retorna_trabalho_submetido($id_participante, $id_inscricao_evento)->titulo_trabalho;

      $area_pos = new AreaPosMat();

      $consolida_escolha['area_trabalho'] = $area_pos->pega_area_pos_mat($id_area_trabalho, $locale_participante);

      $consolida_escolha['titulo_trabalho'] = $titulo_trabalho;
    }

    $consolida_escolha['apresentar_trabalho'] = $escolha_participacao->apresentar_trabalho;

    $consolida_escolha['tipo_apresentacao'] = $tipo_apresentacao->retorna_nome_tipo_participacao_por_id($escolha_participacao->id_tipo_apresentacao, $locale_participante);

    return $consolida_escolha;
  }

  public function ConsolidaNomeArquivos($local_arquivos_temporarios, $local_arquivos_definitivos, $dados_candidato_para_relatorio)
  {

    
    $nome_arquivos = [];

    if (!$dados_candidato_para_relatorio['apresentar_trabalho']) {
      $dados_candidato_para_relatorio['area_trabalho'] = "";
    }
    
    $nome_arquivos['arquivo_relatorio_participante_temporario'] = $local_arquivos_temporarios.'Inscricao_'.str_replace('\'s','',str_replace(' ', '-', strtr($dados_candidato_para_relatorio['area_trabalho'], $this->normalizeChars))).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['tipo_apresentacao'], $this->normalizeChars)).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['nome'], $this->normalizeChars)).'_'.$dados_candidato_para_relatorio['id_participante'].'.pdf';

    $nome_arquivos['arquivo_relatorio_participante'] = $local_arquivos_definitivos.'Inscricao_'.str_replace('\'s','',str_replace(' ', '-', strtr($dados_candidato_para_relatorio['area_trabalho'], $this->normalizeChars))).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['tipo_apresentacao'], $this->normalizeChars)).'_'.str_replace(' ', '-', strtr($dados_candidato_para_relatorio['nome'], $this->normalizeChars)).'_'.$dados_candidato_para_relatorio['id_participante'].'.pdf';

    return $nome_arquivos;
  }

  public function ConsolidaFichaRelatorio($id_participante, $id_inscricao_evento, $nome_arquivos)
  {
    
    $arquivo_pdf_tex = str_replace("storage", "/var/www/inscricoeseventos/storage/app/public", $this->geraAbstract($id_participante, $id_inscricao_evento));

    $process = new Process('pdftk '.$nome_arquivos['arquivo_relatorio_participante_temporario'].' '.$arquivo_pdf_tex.' cat output '.$nome_arquivos['arquivo_relatorio_participante']);

    $process->setTimeout(3600);
    
    $process->run();

    if (!$process->isSuccessful()) {
      throw new ProcessFailedException($process);
    }

    @unlink($nome_arquivos['arquivo_relatorio_participante_temporario']);

    @unlink($arquivo_pdf_tex);
  }

  public function ConsolidaArquivosZIP($id_user, $id_inscricao_evento, $edital, $arquivo_zip, $local_relatorios, $programas)
  {
    $locale_relatorio = 'pt-br';

    $coordenador = new TipoCoordenador();

    $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_user, $id_inscricao_evento);
    
    $id_area_coordenador = $nivel_coordenador->coordenador_area;

    if ($nivel_coordenador->coordenador_geral) {
      $programa_para_relatorio = "Relatorio_Geral";
    }else{
      $area_pos = new AreaPosMat();

      $coordenador_nome_area = str_replace(' ', '-', strtr($area_pos->pega_area_pos_mat($id_area_coordenador, $locale_relatorio), $this->normalizeChars));

      $programa_para_relatorio = "Relatorio_de_".$coordenador_nome_area;
    }

    // $nome_programa_pos = new ProgramaPos();

    // $programa_para_relatorio = strtr($nome_programa_pos->pega_programa_pos_mat($programas, $locale_relatorio), $this->normalizeChars);
    
    
    
    $inscricoes_zipadas = 'Inscricoes_'.$programa_para_relatorio.'.zip';
    
    $arquivos_zipados_para_view[$programas] = $inscricoes_zipadas;

    $zip = new ZipArchive;
    @unlink($arquivo_zip.$inscricoes_zipadas);
    if ( $zip->open( $arquivo_zip.$inscricoes_zipadas, ZipArchive::CREATE ) === true ){
      if ($nivel_coordenador->coordenador_geral) {
        foreach (glob( $local_relatorios.'Inscricao_*') as $fileName ){
          $file = basename( $fileName );
          $zip->addFile( $fileName, $file );
        }
      }else{
        foreach (glob( $local_relatorios.'Inscricao_'.$coordenador_nome_area.'*') as $fileName ){
          $file = basename( $fileName );
          $zip->addFile( $fileName, $file );
        }
      }
     

     $zip->close();
    }

    foreach (glob( $local_relatorios.'Inscricao_*.pdf') as $fileName ){
      @unlink($fileName);
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

    $finalizada = new FinalizaInscricao();

    $total_inscricoes_recebidas = $finalizada->total_inscritos($id_inscricao_evento);

    $coordenador = new TipoCoordenador();

    $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

    $coordenador_area = $nivel_coordenador->coordenador_area;

    $trabalho_submetido = new TrabalhoSubmetido();

    if ($nivel_coordenador->coordenador_geral) {
      
      $areas_com_trabalho = $trabalho_submetido->retorna_area_com_trabalho_submentido($coordenador_area, $id_inscricao_evento);

      foreach ($areas_com_trabalho as $area) {
      
        $contagem[$area] = $this->ContaInscricoes($id_inscricao_evento, $area);
      }

      $area_trabalho = "Todas as Áreas";

      $total_trabalhos_submetidos = array_sum($contagem);
    }else{
      
      $areas_com_trabalho = $trabalho_submetido->retorna_area_com_trabalho_submentido($coordenador_area, $id_inscricao_evento);

      foreach ($areas_com_trabalho as $area) {
      
        $contagem[$area] = $this->ContaInscricoes($id_inscricao_evento, $area);

        $area_pos = new AreaPosMat();

        $area_trabalho = $area_pos->pega_area_pos_mat($area, $locale_relatorio);
      }

      $total_trabalhos_submetidos = array_sum($contagem);
    }

    $arquivos_zipados_para_view = "";

    $documentos_zipados = "";

    $relatorio_csv = "";

    $monitoria = "";

    // return view('templates.partials.coordenador.relatorio_trabalhos_submetidos')->with(compact('monitoria','relatorio_disponivel', 'cursos_ofertados', 'total_trabalhos_submetidos', 'contagem', 'arquivos_zipados_para_view','relatorio_csv'));
    
     return view('templates.partials.coordenador.relatorio_trabalhos_submetidos')->with(compact('area_trabalho', 'monitoria','relatorio_disponivel', 'cursos_ofertados', 'total_trabalhos_submetidos', 'total_inscricoes_recebidas', 'arquivos_zipados_para_view','relatorio_csv'));
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


  public function getArquivosRelatorios($id_user, $id_inscricao_evento,$arquivos_zipados_para_view,$relatorio_csv)
  {

    $locale_relatorio = 'pt-br';

    $relatorio = new ConfiguraInscricaoEvento();

    $relatorio_disponivel = $relatorio->retorna_edital_vigente();
    
    $id_inscricao_evento = $relatorio_disponivel->id_inscricao_evento;

    $finalizada = new FinalizaInscricao();

    $total_inscricoes_recebidas = $finalizada->total_inscritos($id_inscricao_evento);

    $programas_disponiveis = explode("_", $relatorio->retorna_inscricao_ativa()->tipo_evento);

    $id_coordenador = $id_user;

    $coordenador = new TipoCoordenador();

    $nivel_coordenador = $coordenador->retorna_dados_coordenador($id_coordenador, $id_inscricao_evento);

    $coordenador_area = $nivel_coordenador->coordenador_area;

    $trabalho_submetido = new TrabalhoSubmetido();

    if ($nivel_coordenador->coordenador_geral) {
      
      $areas_com_trabalho = $trabalho_submetido->retorna_area_com_trabalho_submentido($coordenador_area, $id_inscricao_evento);

      foreach ($areas_com_trabalho as $area) {
      
        $contagem[$area] = $this->ContaInscricoes($id_inscricao_evento, $area);
      }

      $area_trabalho = "Todas as Áreas";

      $total_trabalhos_submetidos = array_sum($contagem);
    }else{
      
      $areas_com_trabalho = $trabalho_submetido->retorna_area_com_trabalho_submentido($coordenador_area, $id_inscricao_evento);

      foreach ($areas_com_trabalho as $area) {
      
        $contagem[$area] = $this->ContaInscricoes($id_inscricao_evento, $area);

        $area_pos = new AreaPosMat();

        $area_trabalho = $area_pos->pega_area_pos_mat($area, $locale_relatorio);
      }

      $total_trabalhos_submetidos = array_sum($contagem);
    }

    
    // $total_trabalhos_submetidos = 0;
    // $nome_programas = implode('/', $programa_para_inscricao);
    $nome_programas = "teste";

    $monitoria = $id_inscricao_evento;

    $local_arquivos = $this->ConsolidaLocaisArquivos($relatorio_disponivel->ano_evento);

    $endereco_zip_mudar = '/var/www/inscricoeseventos/storage/app/public/';

    //Para ser usado no MAT
    // $endereco_zip_mudar = '/var/www/inscricoesverao/storage/app/public/';

    $local_arquivos['local_relatorios'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['local_relatorios']);

    $local_arquivos['arquivo_zip'] = str_replace($endereco_zip_mudar, 'storage/', $local_arquivos['arquivo_zip']);

    return view('templates.partials.coordenador.relatorio_trabalhos_submetidos')->with(compact('area_trabalho','monitoria','total_inscricoes_recebidas', 'total_trabalhos_submetidos', 'cursos_ofertados', 'relatorio_disponivel','arquivos_zipados_para_view','relatorio_csv','local_arquivos'));

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

    $tipo_cabecalho = "lista_trabalhos_submetidos";

    $relatorio_csv->insertOne($this->ConsolidaCabecalhoCSV($tipo_cabecalho));

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

      $linha_arquivo['categoria_participante'] = $dados_candidato_para_relatorio['categoria_participante'];

      $linha_arquivo['area_trabalho'] = $dados_candidato_para_relatorio['area_trabalho'];

      $linha_arquivo['tipo_apresentacao'] = $dados_candidato_para_relatorio['tipo_apresentacao'];

      $linha_arquivo['titulo_trabalho'] = $dados_candidato_para_relatorio['titulo_trabalho'];

      
      $nome_arquivos = [];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);
      
      $pdf = PDF::loadView('templates.partials.coordenador.pdf_relatorio', compact('dados_candidato_para_relatorio','recomendantes_candidato'));
      $pdf->save($nome_arquivos['arquivo_relatorio_participante_temporario']);

      $this->ConsolidaFichaRelatorio($dados_candidato_para_relatorio['id_participante'], $id_inscricao_evento, $nome_arquivos);

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($user->id_user, $id_inscricao_evento, $relatorio->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio->tipo_evento);

    return $this->getArquivosRelatorios($id_coordenador, $id_inscricao_evento, $arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }

  public function geraRelatorioCSV($id_inscricao_evento)
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
      
      $nome_arquivos = [];

      $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['local_relatorios'], $dados_candidato_para_relatorio);    

      $relatorio_csv->insertOne($linha_arquivo);
      
    }

    $arquivos_zipados_para_view = '';

    return $this->getArquivosRelatorios($id_coordenador, $id_inscricao_evento, $arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
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

    $arquivos_zipados_para_view = $this->ConsolidaArquivosZIP($id_user, $id_inscricao_evento, $relatorio_disponivel->ano_evento, $locais_arquivos['arquivo_zip'], $locais_arquivos['local_relatorios'], $relatorio_disponivel->tipo_evento);

    return $this->getArquivosRelatoriosAnteriores($id_inscricao_evento,$arquivos_zipados_para_view, $locais_arquivos['arquivo_relatorio_csv']);
  }


  public function getRelatorioPos()
  {

    return view('templates.partials.coordenador.relatorio_trabalhos_submetidos');
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

    $nome_arquivos = $this->ConsolidaNomeArquivos($locais_arquivos['arquivos_temporarios'], $locais_arquivos['ficha_inscricao'], $dados_candidato_para_relatorio);
    
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