<?php

namespace Veraomat\Http\Controllers;

use Auth;
use DB;
use Mail;
use Session;
use File;
use ZipArchive;
use PDF;
use Imagick;
use Charts;
use Veraomat\Http\Controllers\FPDFController;
use Carbon\Carbon;
use Veraomat\Models\User;
use Veraomat\Models\ConfiguraInscricaoPos;
use Veraomat\Models\FinalizaInscricao;
use Veraomat\Models\DadoPessoal;
use Veraomat\Models\Paises;
use Veraomat\Models\Formacao;
use Veraomat\Models\Estado;
use Veraomat\Models\Cidade;
use Veraomat\Models\DadoRecomendante;
use Veraomat\Models\DadoAcademico;
use Veraomat\Models\Documento;
use Veraomat\Models\EscolhaCandidato;
use Veraomat\Models\ContatoRecomendante;
use Veraomat\Models\CartaMotivacao;
use Veraomat\Models\CartaRecomendacao;
use Veraomat\Models\AreaPosMat;
use Veraomat\Models\ProgramaPos;
use Illuminate\Http\Request;
use Veraomat\Mail\EmailVerification;
use Veraomat\Http\Controllers\Controller;
use Veraomat\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GraficosController extends BaseController
{   
    private $locale_default = 'pt-br';

	public function index()
    {

        $user = Auth::user();
        
        $relatorio = new ConfiguraInscricaoPos();

        $relatorio_disponivel = $relatorio->retorna_edital_vigente();

        $relatorio_para_grafico = new RelatorioController;

        $programas_disponiveis = explode("_", $relatorio_disponivel->retorna_inscricao_ativa()->programa);

        $nome_programa_pos = new ProgramaPos();

        foreach ($programas_disponiveis as $programa) {

        $programa_para_inscricao[$programa] = $nome_programa_pos->pega_programa_pos_mat($programa, $this->locale_default);
      
        $contagem[$programa_para_inscricao[$programa]] = $relatorio_para_grafico->ContaInscricoes($relatorio_disponivel->id_inscricao_pos, $programa);

        }

        $total_inscritos = array_sum($contagem);

        $inscritos_por_programa = Charts::create('pie', 'highcharts')
                ->title("Inscrições para o Edital ".$relatorio_disponivel->edital)
                ->labels(array_keys($contagem))
                ->values(array_values($contagem))
                ->dimensions(1000,500)
                ->responsive(true);


        if (array_key_exists("Doutorado", $contagem)) {

            $escolhas_candidato = new EscolhaCandidato;

            $areas_inscricoes = $escolhas_candidato->retorna_area_distintas($relatorio_disponivel->id_inscricao_pos);

            $area_pos = new AreaPosMat;

            $nome_area_pos = [];
            $total_por_area = [];

            foreach ($areas_inscricoes as $id_area) {
                $id_por_area = [];

                $total = 0;
                
                $nome_area_pos[] = $area_pos->pega_area_pos_mat($id_area, $this->locale_default);
                
                $id_por_area = $escolhas_candidato->retorna_inscritos_por_area_pos($id_area, $relatorio_disponivel->id_inscricao_pos);
                // dd($id_por_area);
                foreach ($id_por_area as $id_user) {

                    $status_candidato = new FinalizaInscricao;
                    
                    if ($status_candidato->retorna_se_finalizou($id_user, $relatorio_disponivel->id_inscricao_pos)) {
                        ++$total;
                    }
                }

                $total_por_area[] = $total;
            }

            $array_cores = ['red','yellow', '#34aadc', 'orange', '#0088cc', 'green', '#2ecc71', 'gray', 'pink'];

            $numero_de_areas = count($nome_area_pos);
            
            $cores_utilizadas = array_slice($array_cores, 0, $numero_de_areas);
            
            $candidatos_por_area_doutorado = Charts::create('bar', 'highcharts')
                ->title("Inscritos no Doutorado por área/Edital ".$relatorio_disponivel->edital)
                ->responsive(false)
                ->height(400)
                ->width(900)
                ->colors($cores_utilizadas)
                ->template("material")
                ->elementLabel("Total por Área")
                ->values(array_values($total_por_area))
                ->labels(array_values($nome_area_pos))
                ->responsive(true);

            $inscricao_doutorado = TRUE;

            return view('templates.partials.graficos.graficos_pos', compact('inscricao_doutorado','inscritos_por_programa','candidatos_por_area_doutorado'));
        }else{

            $inscricao_doutorado = FALSE;
            return view('templates.partials.graficos.graficos_pos', compact('inscricao_doutorado', 'inscritos_por_programa'));

        }
    }
}