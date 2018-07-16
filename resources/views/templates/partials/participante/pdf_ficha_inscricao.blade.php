<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <style>
            #logo {
                max-width:77px;
            }
            h2 {text-align:center;}
            h4 {text-align:left;}
            label {font-weight: bold;}
            label.motivacao {font-weight: normal;text-align:justify;}
            p.motivacao {font-weight: normal;text-align:justify;}
            .page_break { page-break-before: always;}
            table.tftable {font-size:12px;width:100%;border-width: 1px;border-collapse: collapse;}
    		table.tftable th {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;text-align:center;}
    		table.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;}
            table.tftable td.valor_celula {text-align:center;font-weight: bold;font-size:14px;border-width: 1px;padding: 8px;border-style: solid;}
            table.tftable td.cabecalho {text-align:center;font-size:12px;border-width: 1px;padding: 8px;border-style: solid;}
            .footer {
                width: 100%;
                text-align: center;
                position: fixed;
                font-size: 8pt;
                bottom: 0px;
            }
            .pagenum:before {
                content: counter(page);
            }
            p:last-child { page-break-after: never; }
        </style>
    </head>

    <body>
        <script type="text/php">
            if (isset($pdf)) {
                $font = $fontMetrics->getFont("Arial", "bold");
                $pdf->page_text(35, 750, "{{  $dados_candidato_para_relatorio['nome'] }}", $font, 7, array(0, 0, 0) );
                $pdf->page_text(540, 750, "Página {PAGE_NUM}/{PAGE_COUNT}", $font, 7, array(0, 0, 0));
            }
        </script>

        <p style="width: 500px;">
            <img src="{!! public_path("/imagens/logo/logo_unb_para_relatorios.png") !!}" id="logo" style="float: left;" />
            <h4>
                Departamento de Matemática<br>
            </h4>
        </p>
       
        <h2>
                {{ trans('tela_finalizar_inscricao.ficha_inscricao') }} - {{ $dados_candidato_para_relatorio['nome_evento'] }}</h2>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.nome') }}: </label>{{ $dados_candidato_para_relatorio['nome'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.email') }}: </label>{{ $dados_candidato_para_relatorio['email'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.nome_cracha') }}: </label>{{ $dados_candidato_para_relatorio['nome_cracha'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.numero_documento') }}: </label>{{ $dados_candidato_para_relatorio['numero_documento'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.instituicao') }}: </label>{{ $dados_candidato_para_relatorio['instituicao'] }}
        </div>

        <div>
            <label>{{ trans('tela_dados_pessoais.pais') }}: </label> {{ $dados_candidato_para_relatorio['nome_pais'] }}
        </div>

        <hr>
        <h3>{{ trans('tela_submeter_trabalho.dados_participacao') }}</h3>
        <div>
            <label>{{ trans('tela_submeter_trabalho.categoria') }}: </label>{{ $dados_candidato_para_relatorio['categoria_participante'] }}
        </div>

        <div>
            <label>{{ trans('tela_submeter_trabalho.apresentar_trabalho') }} </label>{{ $dados_candidato_para_relatorio['apresentar_trabalho'] ? trans('tela_submeter_trabalho.sim') : trans('tela_submeter_trabalho.nao') }}
        </div>

        @if ($dados_candidato_para_relatorio['apresentar_trabalho'])
            <div>
            <label>{{ trans('tela_submeter_trabalho.tipo_apresentacao') }} </label>{{ $dados_candidato_para_relatorio['tipo_apresentacao'] }}
        </div>
        @endif
        
    </body>
</html>