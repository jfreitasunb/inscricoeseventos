<?php

namespace InscricoesEventos\Http\Controllers\Coordenador;

use Auth;
use DB;
use Mail;
use Session;
use File;
use PDF;
use Notification;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\AreaPosMat;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\ConfiguraDetalhesEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\TrabalhoSubmetido;
use InscricoesEventos\Models\TrabalhoSelecionado;
use InscricoesEventos\Models\TipoCoordenador;
use InscricoesEventos\Notifications\NotificaNovaInscricao;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\BaseController;
use InscricoesEventos\Http\Controllers\AuthController;
use InscricoesEventos\Http\Controllers\RelatorioController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;
use Response;
use ZipArchive;
use Storage;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
/**
* Classe para visualização da página inicial.
*/
class CadernoResumoController extends CoordenadorController
{

    public function getCadernoResumo()
    {
        File::deleteDirectory($locais_arquivos['caderno_de_resumos']);

        File::isDirectory($locais_arquivos['caderno_de_resumos']) or File::makeDirectory($locais_arquivos['caderno_de_resumos'],0775,true);

        File::copyDirectory( storage_path("app/latex_templates/caderno_de_resumos/"), $locais_arquivos['caderno_de_resumos']);
        
        $arquivo_capa_creditos = $locais_arquivos['caderno_de_resumos']."/obj/cap-cred-pref.tex";

        $aceitos = new TrabalhoSelecionado();

        $trabalhos_aceitos = $aceitos->retorna_trabalhos_selecionados($id_inscricao_evento);

        $detalhes = new ConfiguraDetalhesEvento();

        $detalhes_evento_corrente = $detalhes->retorna_detalhes_evento($id_inscricao_evento);

        $data_inicio_evento = explode("-", $detalhes_evento_corrente->inicio_evento)[2];

        $data_fim_evento = explode("-", $detalhes_evento_corrente->fim_evento)[2];

        $ano_realizacao_evento = explode("-", $detalhes_evento_corrente->inicio_evento)[0];

        $mes_realizacao_evento = $this->array_meses[ltrim(explode("-", $detalhes_evento_corrente->inicio_evento)[1], '0')];

        $edicao = explode("_", $detalhes_evento_corrente->titulo_evento);

        $edicao_verao = explode(" ", $edicao[0])[0];

        $edicao_workshop = explode(" ", $edicao[1])[0];
        
        $str=file_get_contents($arquivo_capa_creditos);

        $str=str_replace("edicao_verao", $edicao_verao, $str);

        $str=str_replace("edicao_workshop", $edicao_workshop, $str);

        $str=str_replace("data_inicio_evento", $data_inicio_evento, $str);

        $str=str_replace("data_fim_evento", $data_fim_evento, $str);

        $str=str_replace("ano_realizacao_evento", $ano_realizacao_evento, $str);

        $str=str_replace("mes_realizacao_evento", $mes_realizacao_evento, $str);

        file_put_contents($arquivo_capa_creditos, $str);

        $dados_resumo = [];
        
        foreach ($trabalhos_aceitos as $aceito) {

            $total_aceitos_por_area = $aceitos->total_trabalhos_por_area($id_inscricao_evento, $aceito->id_area_trabalho);

            $arquivo_area = $locais_arquivos['caderno_de_resumos'].$this->array_arquivos_resumos[$aceito->id_area_trabalho];

            if ($aceito->id_tipo_apresentacao == 1) {

                foreach ($relatorio_controller->ConsolidaDadosPessoais($aceito->id_participante) as $key => $value) {
                 $dados_candidato_para_relatorio[$key] = $value;
                }

                $nome = $this->titleCase($dados_candidato_para_relatorio['nome']);

                $dados_resumo[$nome]['email_autor'] = $dados_candidato_para_relatorio['email'];

                $dados_resumo[$nome]['instituicao_autor'] = $dados_candidato_para_relatorio['instituicao'];

                $trabalho_enviado = new TrabalhoSubmetido();

                $trabalho = $trabalho_enviado->retorna_trabalho_submetido($aceito->id_participante, $id_inscricao_evento);

                $dados_resumo[$nome]['autor_trabalho'] = $trabalho->autor_trabalho;

                $dados_resumo[$nome]['titulo_trabalho'] = $trabalho->titulo_trabalho;

                $dados_resumo[$nome]['abstract_trabalho'] = $trabalho->abstract_trabalho;
                
                if (sizeof($dados_resumo) == $total_aceitos_por_area) {
                    
                    $i = 1;

                    ksort($dados_resumo);
                    
                    $str=file_get_contents($arquivo_area);

                    $parsed = $this->get_string_between($str, '%inicio_bloco_repetir', '%fim_bloco_repetir');
                    
                    foreach ($dados_resumo as $key => $value) {
                        
                        $i++;

                        $words = explode(" ", $key);
                        
                        $label_autor = "";
                        
                        foreach ($words as $letter) {
                            $label_autor .= strtolower(substr($letter, 0, 1));
                        }

                        $str = str_replace("nome_autor", strtr($key, $this->normalize_to_tex), $str);

                        $str = str_replace("email_autor", $value['email_autor'], $str);

                        $str = str_replace("instituicao_autor", strtr($value['instituicao_autor'], $this->normalize_to_tex), $str);

                        $str = str_replace("autor_trabalho", strtr($value['autor_trabalho'], $this->normalize_to_tex), $str);

                        $str = str_replace("titulo_trabalho", strtr($value['titulo_trabalho'], $this->normalize_to_tex), $str);

                        $str = str_replace("texto_trabalho", strtr($value['abstract_trabalho'], $this->normalize_to_tex), $str);

                        $str = str_replace("label_autor", $label_autor, $str);

                        if ($i < $total_aceitos_por_area) {
                            $str .= "%inicio_bloco_repetir\n".$parsed."%fim_bloco_repetir\n";
                        }
                        
                        file_put_contents($arquivo_area, $str);
                    }

                    $str .= "\n\\clearpage";

                    file_put_contents($arquivo_area, $str);
                }
                
            }
        }

        $zip = new ZipArchive;
        
        $caderno_resumo_zipado = 'Caderno_de_Resumos_Evento_'.$relatorio_disponivel->ano_evento.'.zip';

        @unlink($locais_arquivos['arquivo_zip'].$caderno_resumo_zipado);

        $source = $locais_arquivos['arquivo_zip']."caderno_de_resumos/";
        
        if ( $zip->open( $locais_arquivos['local_relatorios'].$caderno_resumo_zipado, ZipArchive::CREATE ) === true ){

            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }

        $zip->close();
    }
}