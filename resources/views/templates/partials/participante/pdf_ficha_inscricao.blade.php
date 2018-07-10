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
                Programa de Pós-Graduação do MAT/UnB
            </h4>
        </p>
       
        <h2>
                {{ trans('tela_finalizar_inscricao.ficha_inscricao') }} - {{ $dados_candidato_para_relatorio['programa_pretendido'] }}</h2>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.nome') }}: </label>{{ $dados_candidato_para_relatorio['nome'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.email') }}: </label>{{ $dados_candidato_para_relatorio['email'] }}
        </div>

        <div>
            <label class="control-label">{{ trans('tela_dados_pessoais.data_nascimento') }}: </label>{{ $dados_candidato_para_relatorio['data_nascimento'] }}
        </div>
        <hr>
        <h3>{{ trans('tela_dados_pessoais.endereco_pessoal') }}</h3>
        <div>
            <label>{{ trans('tela_dados_pessoais.endereco') }}: </label>{{ $dados_candidato_para_relatorio['endereco'] }}
        </div>
        <div>
            <label>{{ trans('tela_dados_pessoais.celular') }}: </label>{{ $dados_candidato_para_relatorio['celular'] }}
        </div>
        <div>
            <label>{{ trans('tela_dados_pessoais.pais') }}: </label> {{ $dados_candidato_para_relatorio['nome_pais'] }}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label> {{ trans('tela_dados_pessoais.estado') }}: </label>{{ $dados_candidato_para_relatorio['nome_estado'] }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>{{ trans('tela_dados_pessoais.cidade') }}: </label> {{ $dados_candidato_para_relatorio['nome_cidade'] }}
        </div>

        <hr>
        <h3>{{ trans('tela_dados_academicos.tela_dados_academicos') }}</h3>
        <div>
            <label>{{ trans('tela_dados_academicos.curso_graduacao') }}: </label> {{ $dados_candidato_para_relatorio['curso_graduacao'] }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>{{ trans('tela_dados_academicos.tipo_curso') }}: </label> {{ $dados_candidato_para_relatorio['tipo_curso_graduacao'] }}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<label>{{ trans('tela_dados_academicos.instituicao') }}: </label> {{ $dados_candidato_para_relatorio['instituicao_graduacao'] }}
        </div>
        <div>
            <label>{{ trans('tela_dados_academicos.ano_conclusao') }}: </label> {{ $dados_candidato_para_relatorio['ano_conclusao_graduacao'] }}
        </div>
        @if ($dados_candidato_para_relatorio['curso_pos'])
        <hr size="0">
        <div>
            <label>{{ trans('tela_dados_academicos.curso_pos') }}: </label> {{ $dados_candidato_para_relatorio['curso_pos'] }} &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <label>{{ trans('tela_dados_academicos.tipo_curso') }}: </label> {{ $dados_candidato_para_relatorio['tipo_curso_pos'] }}&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<label>{{ trans('tela_dados_academicos.instituicao') }}: </label> {{ $dados_candidato_para_relatorio['instituicao_pos'] }}
        </div>
        <div>
            <label>{{ trans('tela_dados_academicos.ano_conclusao') }}: </label> {{ $dados_candidato_para_relatorio['ano_conclusao_pos'] }}
        </div>
        @endif

        <hr>
        <h3>{{ trans('tela_escolha_candidato.tela_escolhas') }}</h3>
        <div>
            <label>{{ trans('tela_escolha_candidato.programa_disponivel') }}: </label>{{ $dados_candidato_para_relatorio['programa_pretendido'] }}
        </div>
        @if ($dados_candidato_para_relatorio['curso_verao'])
            <ul>
                @foreach (explode("_", $dados_candidato_para_relatorio['curso_verao']) as $element)
                    @if ($element<>"")
                        <li>{!! $element !!}</li>
                    @endif
                @endforeach
            </ul>
        @endif
        
    </body>
</html>