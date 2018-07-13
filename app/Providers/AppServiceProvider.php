<?php

namespace InscricoesEventos\Providers;

use Illuminate\Support\ServiceProvider;
use InscricoesEventos\Models\ConfiguraInscricaoEvento;
use Validator;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('is_voluntario', function($attribute, $value, $parameters, $validator) {
            $sponsor_id = array_get($validator->getData(), $parameters[0], null);
            if($value!='somentevoluntaria' && $sponsor_id == "sim"){
                return false;
            }
                return true;
        });

        Validator::extend('tres_recomendantes', function($attribute, $value, $parameters, $validator) {
            if(sizeof(array_unique($value)) <> 3){
                return false;
            }
                return true;
        });

    }

    public function register()
    {
    }
}
