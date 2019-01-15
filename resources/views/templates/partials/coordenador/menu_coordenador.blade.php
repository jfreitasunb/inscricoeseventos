<div class="row">
    <div class="col-sm-3 col-md-2">
        <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseDois"><span class="glyphicon glyphicon-file fa-fw">
                            </span>Inscrições</a>
                        </h4>
                    </div>
                    <div id="collapseDois" class="panel-collapse collapse {{ $keep_open_accordion_inscricoes }}">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('configura.inscricao') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-wrench fa-fw"></span><a href="{{ route('configura.inscricao') }}">Configurar Inscrição</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('cadastra.area.pos') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-pencil fa-fw"></span><a href="{{ route('cadastra.area.pos') }}">Cadastrar novo Curso</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('inscricoes.com.problemas') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-list fa-fw"></span><a href="{{ route('inscricoes.com.problemas') }}">Inscrições Não Finalizadas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('inscricoes.manual') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-list fa-fw"></span><a href="{{ route('inscricoes.manual') }}">Inscrição Manual</a>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td class= "{{ Route::currentRouteNamed('inscricoes.nao.finalizadas') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-list fa-fw"></span><a href="{{ route('inscricoes.nao.finalizadas') }}">Inscrições Não Finalizadas</a>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseTres"><span class="glyphicon glyphicon-file fa-fw">
                            </span>Relatórios</a>
                        </h4>
                    </div>
                    <div id="collapseTres" class="panel-collapse collapse {{ $keep_open_accordion_relatorios }}">
                        <div class="panel-body">
                            <table class="table">
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('relatorio.arquivos.diversos') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-duplicate fa-fw"></span><a href="{{ route('relatorio.arquivos.diversos') }}">Arquivos Diversos</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('relatorio.atual') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-duplicate fa-fw"></span><a href="{{ route('relatorio.atual') }}">Trabalhos Submetidos</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('seleciona.trabalhos.submetidos') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-backward fa-fw"></span><a href="{{ route('seleciona.trabalhos.submetidos') }}">Selecionar Trabalhos</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('caderno.resumos') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-file fa-fw"></span><a href="{{ route('caderno.resumos') }}">Criar Caderno de Resumos</a>
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td class= "{{ Route::currentRouteNamed('gera.ficha.individual') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-file fa-fw"></span><a href="{{ route('gera.ficha.individual') }}">Por Candidato</a>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <span class="glyphicon glyphicon-log-out fa-fw"></span><a href="{{ route('auth.logout') }}">Sair</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9 col-md-10">
            <div class="menuadmin well">
                @yield('cadastra_area_pos')
                @yield('configura_inscricao')
                @yield('edita_area_pos')
                @yield('nao_finalizadas')
                @yield('arquivos_diversos')
                @yield('relatorio_trabalhos_submetidos')
                @yield('seleciona_trabalhos_submentidos')
                @yield('ficha_individual')
                @yield('inscricao_manual')
            </div>
        </div>
    </div>