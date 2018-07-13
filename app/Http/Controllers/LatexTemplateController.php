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
use Exception;
use InscricoesEventos\Http\Controllers\FPDFController;
use Carbon\Carbon;
use InscricoesEventos\Models\User;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use InscricoesEventos\Models\FinalizaInscricao;
use InscricoesEventos\Models\DadoPessoalParticipante;
use InscricoesEventos\Models\TrabalhoSubmetido;
use Illuminate\Http\Request;
use InscricoesEventos\Mail\EmailVerification;
use InscricoesEventos\Http\Controllers\Controller;
use InscricoesEventos\Http\Controllers\AuthController;
use Illuminate\Foundation\Auth\RegistersUsers;
use League\Csv\Writer;
use Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
* Classe para visualização da página inicial.
*/
class LatexTemplateController extends BaseController
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
  
  /**
   * Generate a PDF file using xelatex and pass it to the user
   */
  public static function download($data, $template_file, $outp_file) {
    // Pre-flight checks
    if(!file_exists($template_file)) {
      throw new Exception("Could not open template");
    }

    $local_temp = storage_path("app/public/temp/");

    File::isDirectory($local_temp) or File::makeDirectory($local_temp,0775,true);

    if(($f = tempnam($local_temp, 'tex-')) === false) {
      throw new Exception("Failed to create temporary file");
    }
  
    $tex_f = $f . ".tex";
    $aux_f = $f . ".aux";
    $log_f = $f . ".log";
    $pdf_f = $f . ".pdf";
  
    // Perform substitution of variables
    ob_start();
    include($template_file);
    file_put_contents($tex_f, ob_get_clean());
  
    // Run xelatex (Used because of native unicode and TTF font support)
    $cmd = sprintf("xelatex -interaction nonstopmode -halt-on-error %s",
        escapeshellarg($tex_f));
    chdir($local_temp);
    exec($cmd, $foo, $ret);
  
    // No need for these files anymore
    @unlink($tex_f);
    @unlink($aux_f);
    @unlink($log_f);
    
    // Test here
    if(!file_exists($pdf_f)) {
      @unlink($f);
      throw new Exception("Output was not generated and latex returned: $ret.");
    }
  
    // Send through output
    
    // $fp = fopen($pdf_f, 'rb');
    // header('Content-Type: application/pdf');
    // header('Content-Disposition: attachment; filename="' . $outp_file . '"' );
    // header('Content-Length: ' . filesize($pdf_f));
    // fpassthru($fp);
    
    // Final cleanup
    // @unlink($pdf_f);
    @unlink($f);

    return str_replace("/var/www/InscricoesEventos/storage/app/public","storage",$pdf_f);
  }
  
  /**
   * Series of substitutions to sanitise text for use in LaTeX.
   *
   * http://stackoverflow.com/questions/2627135/how-do-i-sanitize-latex-input
   * Target document should \usepackage{textcomp}
   */
  public static function escape($text) {
    // Prepare backslash/newline handling
    $text = str_replace("\n", "\\\\", $text); // Rescue newlines
    $text = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $text); // Strip all non-printables
    $text = str_replace("\\\\", "\n", $text); // Re-insert newlines and clear \\
    $text = str_replace("\\", "\\\\", $text); // Use double-backslash to signal a backslash in the input (escaped in the final step).
  
    // Symbols which are used in LaTeX syntax
    $text = str_replace("{", "\\{", $text);
    $text = str_replace("}", "\\}", $text);
    $text = str_replace("$", "\\$", $text);
    $text = str_replace("&", "\\&", $text);
    $text = str_replace("#", "\\#", $text);
    $text = str_replace("^", "\\textasciicircum{}", $text);
    $text = str_replace("_", "\\_", $text);
    $text = str_replace("~", "\\textasciitilde{}", $text);
    $text = str_replace("%", "\\%", $text);
  
    // Brackets & pipes
    $text = str_replace("<", "\\textless{}", $text);
    $text = str_replace(">", "\\textgreater{}", $text);
    $text = str_replace("|", "\\textbar{}", $text);
  
    // Quotes
    $text = str_replace("\"", "\\textquotedbl{}", $text);
    $text = str_replace("'", "\\textquotesingle{}", $text);
    $text = str_replace("`", "\\textasciigrave{}", $text);
  
    // Clean up backslashes from before
    $text = str_replace("\\\\", "\\textbackslash{}", $text); // Substitute backslashes from first step.
    $text = str_replace("\n", "\\\\", trim($text)); // Replace newlines (trim is in case of leading \\)
    return $text;
  }

}