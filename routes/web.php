<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/get-cidades/{idEstado}', '\InscricoesEventos\Http\Controllers\CandidatoController@getCidades');

Route::get('api/dependent-dropdown','APIController@index');
Route::get('api/get-state-list','APIController@getStateList');
Route::get('api/get-city-list','APIController@getCityList');

/*
*Área do participante
*/


Route::prefix('participante')->middleware('user.role:participante,admin','define.locale')->group(function () {
	
	Route::get('/', '\InscricoesEventos\Http\Controllers\Participante\ParticipanteController@getMenu')->name('menu.participante');

	Route::get('dados/pessoais', '\InscricoesEventos\Http\Controllers\Participante\DadosPessoaisParticipanteController@getDadosPessoais')->name('dados.pessoais');

	Route::get('dados/pessoais/editar', '\InscricoesEventos\Http\Controllers\Participante\DadosPessoaisParticipanteController@getDadosPessoaisEditar')->name('dados.pessoais.editar');

	Route::post('dados/pessoais', '\InscricoesEventos\Http\Controllers\Participante\DadosPessoaisParticipanteController@postDadosPessoais')->name('dados.pessoais.salvar');

	Route::get('submeter/trabalho', '\InscricoesEventos\Http\Controllers\Participante\SubmeterTrabalhoController@getSubmeterTrabalho')->name('submeter.trabalho');

	Route::post('submeter/trabalho', '\InscricoesEventos\Http\Controllers\Participante\SubmeterTrabalhoController@postSubmeterTrabalho')->name('submeter.trabalho');

	Route::get('finalizar/inscricao', '\InscricoesEventos\Http\Controllers\Participante\FinalizarInscricaoController@getFinalizarInscricao')->name('finalizar.inscricao');

	Route::post('finalizar/inscricao', '\InscricoesEventos\Http\Controllers\Participante\FinalizarInscricaoController@postFinalizarInscricao');
});


/*
*Área do Admin
 */

Route::prefix('admin')->middleware('user.role:admin', 'impersonate.user')->group(function () {

	Route::get('/', '\InscricoesEventos\Http\Controllers\Admin\AdminController@getMenu')->name('menu.admin');

	Route::get('contas/users/impersonate','\InscricoesEventos\Http\Controllers\Admin\ImpersonateController@index')->name('admin.impersonate');

	Route::post('contas/users/impersonate','\InscricoesEventos\Http\Controllers\Admin\ImpersonateController@store');

	Route::delete('contas/users/impersonate','\InscricoesEventos\Http\Controllers\Admin\ImpersonateController@destroy');

	Route::get('contas/criar/coordenador','\InscricoesEventos\Http\Controllers\Admin\CriaCoordenadorController@getCriarCoordenador')->name('criar.coordenador');

	Route::post('contas/criar/coordenador','\InscricoesEventos\Http\Controllers\Admin\CriaCoordenadorController@postCriarCoordenador')->name('criar.coordenador');

	Route::get('contas/users/link/senha', '\InscricoesEventos\Http\Controllers\Admin\LinkSenhaController@getPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::post('contas/users/link/senha', '\InscricoesEventos\Http\Controllers\Admin\LinkSenhaController@postPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::get('contas/pesquisa/conta', '\InscricoesEventos\Http\Controllers\Admin\PesquisaContaController@getPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/pesquisa/conta', '\InscricoesEventos\Http\Controllers\Admin\PesquisaContaController@postPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/altera/conta', '\InscricoesEventos\Http\Controllers\Admin\PesquisaContaController@postAlteraAtivaConta')->name('altera.ativa.conta');

	Route::get('contas/lista/inativos', '\InscricoesEventos\Http\Controllers\Admin\ListaInativosController@getListaInativos')->name('lista.inativos');

	Route::post('contas/lista/inativos', '\InscricoesEventos\Http\Controllers\Admin\ListaInativosController@postListaInativos')->name('lista.inativos');

	Route::get('inscricao/editar', '\InscricoesEventos\Http\Controllers\Admin\EditarInscricaoController@getEditarInscricao')->name('editar.inscricao');

	Route::post('inscricao/editar', '\InscricoesEventos\Http\Controllers\Admin\EditarInscricaoController@postEditarInscricao');

	Route::get('inscricao/reativar/candidato', '\InscricoesEventos\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getReativarInscricaoCandidato')->name('reativar.candidato');

	Route::post('inscricao/pesquisa/candidato', '\InscricoesEventos\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postInscricaoParaReativar')->name('pesquisa.candidato');

	Route::get('inscricao/salvar/alteracao', '\InscricoesEventos\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getSalvaReativacao')->name('salvar.alteracao');

	Route::post('inscricao/salvar/alteracao', '\InscricoesEventos\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postReativarInscricaoCandidato')->name('salvar.alteracao');

	Route::get('chart', '\InscricoesEventos\Http\Controllers\GraficosController@index')->name('ver.charts');

});

Route::resource('admin/datatable/users', 'DataTable\UserController');



/*
*Área do coordenador
 */

Route::prefix('coordenador')->middleware('user.role:coordenador,admin')->group(function () {

	Route::get('/','\InscricoesEventos\Http\Controllers\Coordenador\CoordenadorController@getMenu')->name('menu.coordenador');

	Route::get('configura/inscricao', '\InscricoesEventos\Http\Controllers\Coordenador\ConfiguraInscricaoEventoController@getConfiguraInscricaoEvento')->name('configura.inscricao');

	Route::post('configura/inscricao', '\InscricoesEventos\Http\Controllers\Coordenador\ConfiguraInscricaoEventoController@postConfiguraInscricaoEvento');

	Route::get('cadastra/area/pos', '\InscricoesEventos\Http\Controllers\Coordenador\CadastraCursoVeraoController@getCadastraAreaPos')->name('cadastra.area.pos');

	Route::post('cadastra/area/pos', '\InscricoesEventos\Http\Controllers\Coordenador\CadastraCursoVeraoController@postCadastraAreaPos');

	Route::get('editar/area/pos', '\InscricoesEventos\Http\Controllers\Coordenador\EditarCursoVeraoController@getEditarAreaPos')->name('editar.area.pos');

	Route::post('editar/area/pos', '\InscricoesEventos\Http\Controllers\Coordenador\EditarCursoVeraoController@postEditarAreaPos');

	Route::get('editar/formacao', '\InscricoesEventos\Http\Controllers\Coordenador\EditarFormacaoController@getEditarFormacao')->name('editar.formacao');

	Route::post('editar/formacao', '\InscricoesEventos\Http\Controllers\Coordenador\EditarFormacaoController@postEditarFormacao');

	Route::get('relatorio/arquivos/diversos', '\InscricoesEventos\Http\Controllers\Coordenador\RelatorioEventoController@getGeraArquivosDiversos')->name('relatorio.arquivos.diversos');

	Route::post('relatorio/arquivos/diversos', '\InscricoesEventos\Http\Controllers\Coordenador\RelatorioEventoController@postGeraArquivosDiversos')->name('relatorio.arquivos.diversos');

	Route::get('relatorio/{id_monitoria}', '\InscricoesEventos\Http\Controllers\RelatorioController@geraRelatorio')->name('gera.relatorio');

	Route::get('relatorio', '\InscricoesEventos\Http\Controllers\RelatorioController@getListaRelatorios')->name('relatorio.atual');

	Route::get('relatorio/caderno/resumos', '\InscricoesEventos\Http\Controllers\Coordenador\CadernoResumoController@getCadernoResumo')->name('caderno.resumos');

	Route::post('relatorio/link/acesso', '\InscricoesEventos\Http\Controllers\Coordenador\LinkAcessoController@postLinkAcesso')->name('link.acesso');

	Route::get('gera/ficha/individual', '\InscricoesEventos\Http\Controllers\Coordenador\RelatorioPosController@getFichaInscricaoPorCandidato')->name('gera.ficha.individual');

	Route::get('ver/ficha/individual', '\InscricoesEventos\Http\Controllers\Coordenador\RelatorioPosController@GeraPdfFichaIndividual')->name('ver.ficha.individual');

	Route::get('relatorios/anteriores/{id_monitoria}', '\InscricoesEventos\Http\Controllers\RelatorioController@geraRelatoriosAnteriores')->name('gera.anteriores');

	Route::get('relatorios/anteriores', '\InscricoesEventos\Http\Controllers\RelatorioController@getListaRelatoriosAnteriores')->name('relatorio.anteriores');

	Route::get('inscricoes/nao/finalizadas', '\InscricoesEventos\Http\Controllers\Admin\ListaInscricaoNaoFinalizadasController@getInscricoesNaoFinalizadas')->name('inscricoes.nao.finalizadas');

	Route::get('selecionar/trabalhos', '\InscricoesEventos\Http\Controllers\Coordenador\SelecionaTrabalhosController@getSelecionarTrabalhos')->name('seleciona.trabalhos.submetidos');

	Route::post('selecionar/trabalhos', '\InscricoesEventos\Http\Controllers\Coordenador\SelecionaTrabalhosController@postSelecionarTrabalhos')->name('seleciona.trabalhos.submetidos');
});



/**
* Logout
 */

Route::get('/logout', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@getLogout',
		'as'	=> 'auth.logout',
		'middleware' => ['define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@postLogin',
]);

/**
* Logar
 */

Route::get('/login', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@getLogin',
		'as'	=> 'auth.login',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@postLogin',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('register/verify/{token}',[
	'uses' => '\InscricoesEventos\Http\Controllers\Auth\AuthController@verify',
	'middleware' => ['guest'],
]);

/**
* Registrar
 */
Route::get('/registrar', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@getSignup',
		'as'	=> 'auth.registrar',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

Route::post('/registrar', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@postSignup',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

/*
*Password Reset Routes
 */

Route::get('esqueci/senha', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm',
		'as'	=> 'password.request',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('esqueci/senha/link', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail',
		'as' => 'password.email',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/esqueci/senha/{token}', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\ResetPasswordController@showResetForm',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/esqueci/senha/{token}', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\ResetPasswordController@reset',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/mudousenha', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\Auth\AuthController@getMudouSenha',
		'as'	=> 'mudou.senha',
		'middleware' => ['guest', 'define.locale'],
]);

/**
* Alertas
 */
Route::get('/alert', function () {
	return redirect()->route('home')->with('info', 'Sucess.');
});

/**
* Home
 */
Route::get('/', [
		'uses'	=> '\InscricoesEventos\Http\Controllers\HomeController@index',
		'as'	=> 'home',
		'middleware' => ['define.locale'],
]);

/*
*Seleção de Idioma
*/

Route::get('/ptbr', [
	'uses' => '\InscricoesEventos\Http\Controllers\HomeController@getLangPortuguese',
	'as'   => 'lang.portuguese',
	'middleware' => ['define.locale'],
]);

Route::get('/en', [
	'uses' => '\InscricoesEventos\Http\Controllers\HomeController@getLangEnglish',
	'as'   => 'lang.english',
	'middleware' => ['define.locale'],
]);

Route::get('/es', [
	'uses' => '\InscricoesEventos\Http\Controllers\HomeController@getLangSpanish',
	'as'   => 'lang.spanish',
	'middleware' => ['define.locale'],
]);

Route::get('/migracao', [
	'uses' => '\InscricoesEventos\Http\Controllers\MigracaoController@getMigracao',
	'as'   => 'migra.dados',
	'middleware' =>['user.role:admin']
]);

Route::get('/acesso/arquivos', '\InscricoesEventos\Http\Controllers\Coordenador\AcessoArquivosController@getVerArquivos')->name('ver.arquivos')->middleware('validaassinatura');