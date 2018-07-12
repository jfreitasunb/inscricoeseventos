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

Route::get('/get-cidades/{idEstado}', '\InscricoesEventosMat\Http\Controllers\CandidatoController@getCidades');

Route::get('api/dependent-dropdown','APIController@index');
Route::get('api/get-state-list','APIController@getStateList');
Route::get('api/get-city-list','APIController@getCityList');

/*
*Área do participante
*/


Route::prefix('participante')->middleware('user.role:participante,admin','define.locale')->group(function () {
	
	Route::get('/', '\InscricoesEventosMat\Http\Controllers\Participante\ParticipanteController@getMenu')->name('menu.participante');

	Route::get('dados/pessoais', '\InscricoesEventosMat\Http\Controllers\Participante\DadosPessoaisParticipanteController@getDadosPessoais')->name('dados.pessoais');

	Route::get('dados/pessoais/editar', '\InscricoesEventosMat\Http\Controllers\Participante\DadosPessoaisParticipanteController@getDadosPessoaisEditar')->name('dados.pessoais.editar');

	Route::post('dados/pessoais', '\InscricoesEventosMat\Http\Controllers\Participante\DadosPessoaisParticipanteController@postDadosPessoais')->name('dados.pessoais.salvar');

	Route::get('submeter/trabalho', '\InscricoesEventosMat\Http\Controllers\Participante\SubmeterTrabalhoController@getSubmeterTrabalho')->name('submeter.trabalho');

	Route::post('submeter/trabalho', '\InscricoesEventosMat\Http\Controllers\Participante\SubmeterTrabalhoController@postSubmeterTrabalho')->name('submeter.trabalho');

	Route::get('finalizar/inscricao', '\InscricoesEventosMat\Http\Controllers\Participante\FinalizarInscricaoController@getFinalizarInscricao')->name('finalizar.inscricao');

	Route::post('finalizar/inscricao', '\InscricoesEventosMat\Http\Controllers\Participante\FinalizarInscricaoController@postFinalizarInscricao');
});


/*
*Área do Admin
 */

Route::prefix('admin')->middleware('user.role:admin', 'impersonate.user')->group(function () {

	Route::get('/', '\InscricoesEventosMat\Http\Controllers\Admin\AdminController@getMenu')->name('menu.admin');

	Route::get('contas/users/impersonate','\InscricoesEventosMat\Http\Controllers\Admin\ImpersonateController@index')->name('admin.impersonate');

	Route::post('contas/users/impersonate','\InscricoesEventosMat\Http\Controllers\Admin\ImpersonateController@store');

	Route::delete('contas/users/impersonate','\InscricoesEventosMat\Http\Controllers\Admin\ImpersonateController@destroy');

	Route::get('contas/users/link/senha', '\InscricoesEventosMat\Http\Controllers\Admin\LinkSenhaController@getPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::post('contas/users/link/senha', '\InscricoesEventosMat\Http\Controllers\Admin\LinkSenhaController@postPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::get('contas/pesquisa/conta', '\InscricoesEventosMat\Http\Controllers\Admin\PesquisaContaController@getPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/pesquisa/conta', '\InscricoesEventosMat\Http\Controllers\Admin\PesquisaContaController@postPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/altera/conta', '\InscricoesEventosMat\Http\Controllers\Admin\PesquisaContaController@postAlteraAtivaConta')->name('altera.ativa.conta');

	Route::get('contas/lista/inativos', '\InscricoesEventosMat\Http\Controllers\Admin\ListaInativosController@getListaInativos')->name('lista.inativos');

	Route::post('contas/lista/inativos', '\InscricoesEventosMat\Http\Controllers\Admin\ListaInativosController@postListaInativos')->name('lista.inativos');

	Route::get('inscricao/editar', '\InscricoesEventosMat\Http\Controllers\Admin\EditarInscricaoController@getEditarInscricao')->name('editar.inscricao');

	Route::post('inscricao/editar', '\InscricoesEventosMat\Http\Controllers\Admin\EditarInscricaoController@postEditarInscricao');

	Route::get('inscricao/reativar/candidato', '\InscricoesEventosMat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getReativarInscricaoCandidato')->name('reativar.candidato');

	Route::post('inscricao/pesquisa/candidato', '\InscricoesEventosMat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postInscricaoParaReativar')->name('pesquisa.candidato');

	Route::get('inscricao/salvar/alteracao', '\InscricoesEventosMat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getSalvaReativacao')->name('salvar.alteracao');

	Route::post('inscricao/salvar/alteracao', '\InscricoesEventosMat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postReativarInscricaoCandidato')->name('salvar.alteracao');

	Route::get('chart', '\InscricoesEventosMat\Http\Controllers\GraficosController@index')->name('ver.charts');

});

Route::resource('admin/datatable/users', 'DataTable\UserController');



/*
*Área do coordenador
 */

Route::prefix('coordenador')->middleware('user.role:coordenador,admin')->group(function () {

	Route::get('/','\InscricoesEventosMat\Http\Controllers\Coordenador\CoordenadorController@getMenu')->name('menu.coordenador');

	Route::get('configura/inscricao', '\InscricoesEventosMat\Http\Controllers\Coordenador\ConfiguraInscricaoEventoController@getConfiguraInscricaoEvento')->name('configura.inscricao');

	Route::post('configura/inscricao', '\InscricoesEventosMat\Http\Controllers\Coordenador\ConfiguraInscricaoEventoController@postConfiguraInscricaoEvento');

	Route::get('cadastra/area/pos', '\InscricoesEventosMat\Http\Controllers\Coordenador\CadastraCursoVeraoController@getCadastraAreaPos')->name('cadastra.area.pos');

	Route::post('cadastra/area/pos', '\InscricoesEventosMat\Http\Controllers\Coordenador\CadastraCursoVeraoController@postCadastraAreaPos');

	Route::get('editar/area/pos', '\InscricoesEventosMat\Http\Controllers\Coordenador\EditarCursoVeraoController@getEditarAreaPos')->name('editar.area.pos');

	Route::post('editar/area/pos', '\InscricoesEventosMat\Http\Controllers\Coordenador\EditarCursoVeraoController@postEditarAreaPos');

	Route::get('editar/formacao', '\InscricoesEventosMat\Http\Controllers\Coordenador\EditarFormacaoController@getEditarFormacao')->name('editar.formacao');

	Route::post('editar/formacao', '\InscricoesEventosMat\Http\Controllers\Coordenador\EditarFormacaoController@postEditarFormacao');

	Route::get('relatorio/{id_monitoria}', '\InscricoesEventosMat\Http\Controllers\RelatorioController@geraRelatorio')->name('gera.relatorio');

	Route::get('relatorio', '\InscricoesEventosMat\Http\Controllers\RelatorioController@getListaRelatorios')->name('relatorio.atual');

	Route::get('relatorio/link/acesso', '\InscricoesEventosMat\Http\Controllers\Coordenador\LinkAcessoController@getLinkAcesso')->name('link.acesso');

	Route::post('relatorio/link/acesso', '\InscricoesEventosMat\Http\Controllers\Coordenador\LinkAcessoController@postLinkAcesso')->name('link.acesso');

	Route::get('gera/ficha/individual', '\InscricoesEventosMat\Http\Controllers\Coordenador\RelatorioPosController@getFichaInscricaoPorCandidato')->name('gera.ficha.individual');

	Route::get('ver/ficha/individual', '\InscricoesEventosMat\Http\Controllers\Coordenador\RelatorioPosController@GeraPdfFichaIndividual')->name('ver.ficha.individual');

	Route::get('relatorios/anteriores/{id_monitoria}', '\InscricoesEventosMat\Http\Controllers\RelatorioController@geraRelatoriosAnteriores')->name('gera.anteriores');

	Route::get('relatorios/anteriores', '\InscricoesEventosMat\Http\Controllers\RelatorioController@getListaRelatoriosAnteriores')->name('relatorio.anteriores');

	Route::get('inscricoes/nao/finalizadas', '\InscricoesEventosMat\Http\Controllers\Admin\ListaInscricaoNaoFinalizadasController@getInscricoesNaoFinalizadas')->name('inscricoes.nao.finalizadas');
});



/**
* Logout
 */

Route::get('/logout', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@getLogout',
		'as'	=> 'auth.logout',
		'middleware' => ['define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@postLogin',
]);

/**
* Logar
 */

Route::get('/login', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@getLogin',
		'as'	=> 'auth.login',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@postLogin',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('register/verify/{token}',[
	'uses' => '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@verify',
	'middleware' => ['guest'],
]);

/**
* Registrar
 */
Route::get('/registrar', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@getSignup',
		'as'	=> 'auth.registrar',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

Route::post('/registrar', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@postSignup',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

/*
*Password Reset Routes
 */

Route::get('esqueci/senha', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm',
		'as'	=> 'password.request',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('esqueci/senha/link', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail',
		'as' => 'password.email',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/esqueci/senha/{token}', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\ResetPasswordController@showResetForm',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/esqueci/senha/{token}', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\ResetPasswordController@reset',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/mudousenha', [
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\Auth\AuthController@getMudouSenha',
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
		'uses'	=> '\InscricoesEventosMat\Http\Controllers\HomeController@index',
		'as'	=> 'home',
		'middleware' => ['define.locale'],
]);

/*
*Seleção de Idioma
*/

Route::get('/ptbr', [
	'uses' => '\InscricoesEventosMat\Http\Controllers\HomeController@getLangPortuguese',
	'as'   => 'lang.portuguese',
	'middleware' => ['define.locale'],
]);

Route::get('/en', [
	'uses' => '\InscricoesEventosMat\Http\Controllers\HomeController@getLangEnglish',
	'as'   => 'lang.english',
	'middleware' => ['define.locale'],
]);

Route::get('/es', [
	'uses' => '\InscricoesEventosMat\Http\Controllers\HomeController@getLangSpanish',
	'as'   => 'lang.spanish',
	'middleware' => ['define.locale'],
]);

Route::get('/migracao', [
	'uses' => '\InscricoesEventosMat\Http\Controllers\MigracaoController@getMigracao',
	'as'   => 'migra.dados',
	'middleware' =>['user.role:admin']
]);

Route::get('/acesso/arquivos', '\InscricoesEventosMat\Http\Controllers\Coordenador\AcessoArquivosController@getVerArquivos')->name('ver.arquivos')->middleware('validaassinatura');