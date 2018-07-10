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

Route::get('/get-cidades/{idEstado}', '\Veraomat\Http\Controllers\CandidatoController@getCidades');

Route::get('api/dependent-dropdown','APIController@index');
Route::get('api/get-state-list','APIController@getStateList');
Route::get('api/get-city-list','APIController@getCityList');

/*
*Área do candidato
*/


Route::prefix('candidato')->middleware('user.role:candidato,admin','define.locale')->group(function () {
	
	Route::get('/', '\Veraomat\Http\Controllers\Candidato\CandidatoController@getMenu')->name('menu.candidato');

	Route::get('dados/pessoais', '\Veraomat\Http\Controllers\Candidato\DadosPessoaisController@getDadosPessoais')->name('dados.pessoais');

	Route::get('dados/pessoais/editar', '\Veraomat\Http\Controllers\Candidato\DadosPessoaisController@getDadosPessoaisEditar')->name('dados.pessoais.editar');

	Route::post('dados/pessoais', '\Veraomat\Http\Controllers\Candidato\DadosPessoaisController@postDadosPessoais')->name('dados.pessoais.salvar');

	Route::get('dados/academicos', '\Veraomat\Http\Controllers\Candidato\DadosAcademicosController@getDadosAcademicos')->name('dados.academicos');

	Route::post('dados/academicos', '\Veraomat\Http\Controllers\Candidato\DadosAcademicosController@postDadosAcademicos');

	Route::get('dados/escolhas', '\Veraomat\Http\Controllers\Candidato\EscolhaCandidatoController@getEscolhaCandidato')->name('dados.escolhas');

	Route::post('dados/escolhas', '\Veraomat\Http\Controllers\Candidato\EscolhaCandidatoController@postEscolhaCandidato');

	Route::get('motivacao/documentos', '\Veraomat\Http\Controllers\Candidato\MotivacaoDocumentosController@getMotivacaoDocumentos')->name('motivacao.documentos');

	Route::post('motivacao/documentos', '\Veraomat\Http\Controllers\Candidato\MotivacaoDocumentosController@postMotivacaoDocumentos');

	Route::get('finalizar/inscricao', '\Veraomat\Http\Controllers\Candidato\FinalizarInscricaoController@getFinalizarInscricao')->name('finalizar.inscricao');

	Route::post('finalizar/inscricao', '\Veraomat\Http\Controllers\Candidato\FinalizarInscricaoController@postFinalizarInscricao');

	Route::get('status/cartas', '\Veraomat\Http\Controllers\Candidato\StatusCartasController@getStatusCartas')->name('status.cartas');

	Route::post('status/cartas', '\Veraomat\Http\Controllers\Candidato\StatusCartasController@postStatusCartas');
});


/*
*Área do Admin
 */

Route::prefix('admin')->middleware('user.role:admin', 'impersonate.user')->group(function () {

	Route::get('/', '\Veraomat\Http\Controllers\Admin\AdminController@getMenu')->name('menu.admin');

	Route::get('contas/users/impersonate','\Veraomat\Http\Controllers\Admin\ImpersonateController@index')->name('admin.impersonate');

	Route::post('contas/users/impersonate','\Veraomat\Http\Controllers\Admin\ImpersonateController@store');

	Route::delete('contas/users/impersonate','\Veraomat\Http\Controllers\Admin\ImpersonateController@destroy');

	Route::get('contas/users/link/senha', '\Veraomat\Http\Controllers\Admin\LinkSenhaController@getPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::post('contas/users/link/senha', '\Veraomat\Http\Controllers\Admin\LinkSenhaController@postPesquisaLinkMudarSenha')->name('pesquisa.email.muda.senha');

	Route::get('contas/pesquisa/conta', '\Veraomat\Http\Controllers\Admin\PesquisaContaController@getPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/pesquisa/conta', '\Veraomat\Http\Controllers\Admin\PesquisaContaController@postPesquisaConta')->name('pesquisa.usuario');

	Route::post('contas/altera/conta', '\Veraomat\Http\Controllers\Admin\PesquisaContaController@postAlteraAtivaConta')->name('altera.ativa.conta');

	Route::get('contas/lista/inativos', '\Veraomat\Http\Controllers\Admin\ListaInativosController@getListaInativos')->name('lista.inativos');

	Route::post('contas/lista/inativos', '\Veraomat\Http\Controllers\Admin\ListaInativosController@postListaInativos')->name('lista.inativos');

	Route::get('inscricao/editar', '\Veraomat\Http\Controllers\Admin\EditarInscricaoController@getEditarInscricao')->name('editar.inscricao');

	Route::post('inscricao/editar', '\Veraomat\Http\Controllers\Admin\EditarInscricaoController@postEditarInscricao');

	Route::get('inscricao/reativar/candidato', '\Veraomat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getReativarInscricaoCandidato')->name('reativar.candidato');

	Route::post('inscricao/pesquisa/candidato', '\Veraomat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postInscricaoParaReativar')->name('pesquisa.candidato');

	Route::get('inscricao/salvar/alteracao', '\Veraomat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@getSalvaReativacao')->name('salvar.alteracao');

	Route::post('inscricao/salvar/alteracao', '\Veraomat\Http\Controllers\Admin\ReativarInscricaoCandidatoController@postReativarInscricaoCandidato')->name('salvar.alteracao');

	Route::get('chart', '\Veraomat\Http\Controllers\GraficosController@index')->name('ver.charts');

});

Route::resource('admin/datatable/users', 'DataTable\UserController');



/*
*Área do coordenador
 */

Route::prefix('coordenador')->middleware('user.role:coordenador,admin')->group(function () {

	Route::get('/','\Veraomat\Http\Controllers\Coordenador\CoordenadorController@getMenu')->name('menu.coordenador');

	Route::get('configura/inscricao', '\Veraomat\Http\Controllers\Coordenador\ConfiguraInscricaoPosController@getConfiguraInscricaoPos')->name('configura.inscricao');

	Route::post('configura/inscricao', '\Veraomat\Http\Controllers\Coordenador\ConfiguraInscricaoPosController@postConfiguraInscricaoPos');

	Route::get('cadastra/area/pos', '\Veraomat\Http\Controllers\Coordenador\CadastraCursoVeraoController@getCadastraAreaPos')->name('cadastra.area.pos');

	Route::post('cadastra/area/pos', '\Veraomat\Http\Controllers\Coordenador\CadastraCursoVeraoController@postCadastraAreaPos');

	Route::get('editar/area/pos', '\Veraomat\Http\Controllers\Coordenador\EditarCursoVeraoController@getEditarAreaPos')->name('editar.area.pos');

	Route::post('editar/area/pos', '\Veraomat\Http\Controllers\Coordenador\EditarCursoVeraoController@postEditarAreaPos');

	Route::get('editar/formacao', '\Veraomat\Http\Controllers\Coordenador\EditarFormacaoController@getEditarFormacao')->name('editar.formacao');

	Route::post('editar/formacao', '\Veraomat\Http\Controllers\Coordenador\EditarFormacaoController@postEditarFormacao');

	Route::get('relatorio/{id_monitoria}', '\Veraomat\Http\Controllers\RelatorioController@geraRelatorio')->name('gera.relatorio');

	Route::get('relatorio', '\Veraomat\Http\Controllers\RelatorioController@getListaRelatorios')->name('relatorio.atual');

	Route::get('relatorio/link/acesso', '\Veraomat\Http\Controllers\Coordenador\LinkAcessoController@getLinkAcesso')->name('link.acesso');

	Route::post('relatorio/link/acesso', '\Veraomat\Http\Controllers\Coordenador\LinkAcessoController@postLinkAcesso')->name('link.acesso');

	Route::get('gera/ficha/individual', '\Veraomat\Http\Controllers\Coordenador\RelatorioPosController@getFichaInscricaoPorCandidato')->name('gera.ficha.individual');

	Route::get('ver/ficha/individual', '\Veraomat\Http\Controllers\Coordenador\RelatorioPosController@GeraPdfFichaIndividual')->name('ver.ficha.individual');

	Route::get('relatorios/anteriores/{id_monitoria}', '\Veraomat\Http\Controllers\RelatorioController@geraRelatoriosAnteriores')->name('gera.anteriores');

	Route::get('relatorios/anteriores', '\Veraomat\Http\Controllers\RelatorioController@getListaRelatoriosAnteriores')->name('relatorio.anteriores');

	Route::get('inscricoes/nao/finalizadas', '\Veraomat\Http\Controllers\Admin\ListaInscricaoNaoFinalizadasController@getInscricoesNaoFinalizadas')->name('inscricoes.nao.finalizadas');
});



/**
* Logout
 */

Route::get('/logout', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@getLogout',
		'as'	=> 'auth.logout',
		'middleware' => ['define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@postLogin',
]);

/**
* Logar
 */

Route::get('/login', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@getLogin',
		'as'	=> 'auth.login',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/login', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@postLogin',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('register/verify/{token}',[
	'uses' => '\Veraomat\Http\Controllers\Auth\AuthController@verify',
	'middleware' => ['guest'],
]);

/**
* Registrar
 */
Route::get('/registrar', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@getSignup',
		'as'	=> 'auth.registrar',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

Route::post('/registrar', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@postSignup',
		'middleware' => ['guest','autoriza.inscricao','define.locale']
]);

/*
*Password Reset Routes
 */

Route::get('esqueci/senha', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm',
		'as'	=> 'password.request',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('esqueci/senha/link', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail',
		'as' => 'password.email',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/esqueci/senha/{token}', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\ResetPasswordController@showResetForm',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::post('/esqueci/senha/{token}', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\ResetPasswordController@reset',
		'as' => 'password.reset',
		'middleware' => ['guest', 'define.locale'],
]);

Route::get('/mudousenha', [
		'uses'	=> '\Veraomat\Http\Controllers\Auth\AuthController@getMudouSenha',
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
		'uses'	=> '\Veraomat\Http\Controllers\HomeController@index',
		'as'	=> 'home',
		'middleware' => ['define.locale'],
]);

/*
*Seleção de Idioma
*/

Route::get('/ptbr', [
	'uses' => '\Veraomat\Http\Controllers\HomeController@getLangPortuguese',
	'as'   => 'lang.portuguese',
	'middleware' => ['define.locale'],
]);

Route::get('/en', [
	'uses' => '\Veraomat\Http\Controllers\HomeController@getLangEnglish',
	'as'   => 'lang.english',
	'middleware' => ['define.locale'],
]);

Route::get('/es', [
	'uses' => '\Veraomat\Http\Controllers\HomeController@getLangSpanish',
	'as'   => 'lang.spanish',
	'middleware' => ['define.locale'],
]);

Route::get('/migracao', [
	'uses' => '\Veraomat\Http\Controllers\MigracaoController@getMigracao',
	'as'   => 'migra.dados',
	'middleware' =>['user.role:admin']
]);

Route::get('/acesso/arquivos', '\Veraomat\Http\Controllers\Coordenador\AcessoArquivosController@getVerArquivos')->name('ver.arquivos')->middleware('validaassinatura');