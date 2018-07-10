<?php

namespace Veraomat\Console\Commands;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Notification;
use Veraomat\Models\{User, ConfiguraInscricaoPos, DadoRecomendante, CartaRecomendacao};
use Veraomat\Notifications\EmailRememberRecomendante;

use Illuminate\Console\Command;

class RememberRecomendante extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reme:reco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lembra os recomendantes do prazo final de envio das cartas.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $edital = new ConfiguraInscricaoPos;

        $edital_vigente = $edital->retorna_inscricao_ativa();

        $prazo_carta = Carbon::createFromFormat('Y-m-d', $edital_vigente->prazo_carta);

        $cartas = new CartaRecomendacao;

        $cartas_nao_enviadas = $cartas->retorna_carta_recomendacao_nao_enviadas($edital_vigente->id_inscricao_pos);

        $data_hoje = Carbon::now();

        $dados_email['prazo_carta'] = $prazo_carta->format('d/m/Y');

        if ($data_hoje->diffInDays($prazo_carta) == 2) {
           
           foreach ($cartas_nao_enviadas as $id_user) {
               
               $dado_pessoal_recomendante = new DadoRecomendante();

               $dados_recomendantes = $dado_pessoal_recomendante->retorna_dados_pessoais_recomendante($id_user);
               
               if (!is_null($dados_recomendantes)) {
                   
                   $dados_email['nome_professor'] = $dado_pessoal_recomendante->retorna_dados_pessoais_recomendante($id_user)->nome_recomendante;

                    Notification::send(User::find($id_user), new EmailRememberRecomendante($dados_email));
               }   
           }
        }
    }
}
