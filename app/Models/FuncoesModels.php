<?php

namespace InscricoesEventos\Models;

use DB;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class FuncoesModels extends Model
{
    

    public function define_nome_coluna_area_pos_mat($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_en';
                break;

            case 'es':
                return 'nome_es';
                break;
            
            default:
                return 'nome_ptbr';
                break;
        }
    }

    public function define_nome_coluna_programa_pos_mat($locale)
    {
        switch ($locale) {
            case 'en':
                return 'tipo_programa_pos_en';
                break;

            case 'es':
                return 'tipo_programa_pos_es';
                break;
            
            default:
                return 'tipo_programa_pos_ptbr';
                break;
        }
    }

    public function define_nome_coluna_tipo_programa_pos($locale)
    {
        switch ($locale) {
            case 'en':
                return 'tipo_programa_pos_en';
                break;

            case 'es':
                return 'tipo_programa_pos_es';
                break;
            
            default:
                return 'tipo_programa_pos_ptbr';
                break;
        }
    }

    public function define_nome_coluna_tipo_formacao($locale)
    {
        switch ($locale) {
            case 'en':
                return 'tipo_en';
                break;

            case 'es':
                return 'tipo_es';
                break;
            
            default:
                return 'tipo_ptbr';
                break;
        }
    }

    public function define_nome_coluna_tipo_apresentacao($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_tipo_apresentacao_en';
                break;

            case 'es':
                return 'nome_tipo_apresentacao_es';
                break;
            
            default:
                return 'nome_tipo_apresentacao_ptbr';
                break;
        }
    }

    public function define_nome_coluna_categoria($locale)
    {
        switch ($locale) {
            case 'en':
                return 'nome_categoria_en';
                break;

            case 'es':
                return 'nome_categoria_es';
                break;
            
            default:
                return 'nome_categoria_ptbr';
                break;
        }
    }
}