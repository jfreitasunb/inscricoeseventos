<div class="row">
    <div class="col-sm-3 col-md-2">
        <div class="panel-group" id="accordion">
            <div class="menuadmin panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseUm"><span class="glyphicon glyphicon-user fa-fw">
                        </span>Contas</a>
                    </h4>
                </div>
                <div id="collapseUm" class="panel-collapse collapse {{ $keep_open_accordion_contas }}">
                    <div class="panel-body">
                        <table class="table">
                            <tr>
                                <td class= "{{ Route::currentRouteNamed('pesquisa.email.muda.senha') ? 'active_link' : '' }}">
                                    <span class="glyphicon glyphicon-cog fa-fw"></span><a href="{{ route('pesquisa.email.muda.senha') }}">Link mudança de senha</a>
                                </td>
                            </tr>
                            <tr>
                                <td class= "{{ Route::currentRouteNamed('admin.impersonate') ? 'active_link' : '' }}">
                                    <span class="glyphicon glyphicon-cog fa-fw"></span><a href="{{ route('admin.impersonate') }}">Logar como</a>
                                </td>
                            </tr>
                            <tr>
                                <td class= "{{ Route::currentRouteNamed('pesquisa.usuario') ? 'active_link' : '' }}">
                                    <span class="glyphicon glyphicon-cog fa-fw"></span><a href="{{ route('pesquisa.usuario') }}">Ativar/Alterar conta</a>
                                </td>
                            </tr>
                            <tr>
                                <td class= "{{ Route::currentRouteNamed('criar.coordenador') ? 'active_link' : '' }}">
                                    <span class="glyphicon glyphicon-cog fa-fw"></span><a href="{{ route('criar.coordenador') }}">Cria Coordenador</a>
                                </td>
                            </tr>
                            <tr>
                                <td class= "{{ Route::currentRouteNamed('lista.inativos') ? 'active_link' : '' }}">
                                    <span class="glyphicon glyphicon-cog fa-fw"></span><a href="{{ route('lista.inativos') }}">Usuários Inativos</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    </div>
                </div>
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
                                    <td class= "{{ Route::currentRouteNamed('editar.inscricao') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-pencil fa-fw"></span><a href="{{ route('editar.inscricao') }}">Editar Inscrição</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('reativar.candidato') || Route::currentRouteNamed('pesquisa.candidato') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-refresh fa-fw"></span><a href="{{ route('reativar.candidato') }}">Reativar Inscrição Candidato</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('inscricoes.com.problemas') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-list fa-fw"></span><a href="{{ route('inscricoes.com.problemas') }}">Inscrições Não Finalizadas</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class= "{{ Route::currentRouteNamed('inscricao.manual') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-list fa-fw"></span><a href="{{ route('inscricao.manual') }}">Inscrição Manual</a>
                                    </td>
                                </tr>
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
                                    <td class= "{{ Route::currentRouteNamed('ver.charts') ? 'active_link' : '' }}">
                                        <span class="glyphicon glyphicon-stats fa-fw"></span><a href="{{ route('ver.charts') }}">Gráficos</a>
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
                @yield('admin_impersonate')
                @yield('ativa_conta')
                @yield('criar_coordenador')
                @yield('link_muda_senha')
                @yield('contas_inativas')
                @yield('visualiza_associacoes')
                @yield('cadastra_disciplina')
                @yield('configura_inscricao')
                @yield('editar_inscricao')
                @yield('reativar_inscricao_candidato')
                @yield('reativar_carta_enviada')
                @yield('acha_indicacoes')
                @yield('associa_recomendate')
                @yield('nao_finalizadas')
                @yield('tabela_indicacoes')
                @yield('altera_recomendantes')
                @yield('arquivos_diversos')
                @yield('relatorio_trabalhos_submetidos')
                @yield('seleciona_trabalhos_submentidos')
                @yield('datatable_users')
                @yield('ficha_individual')
                @yield('graficos')
                @yield('inscricao_manual')
            </div>
        </div>
    </div>