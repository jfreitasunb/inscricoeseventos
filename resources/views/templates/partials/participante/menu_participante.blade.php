<div class="row">
  <nav class="navbar navbar-default col-md-8 col-md-offset-2" role="navigation">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bar1">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse" id="bar1">
      <ul class="nav navbar-nav">
        <li class="{{ Route::currentRouteNamed('dados.pessoais') ? 'active' : '' }}"><a href="{{ route('dados.pessoais') }}">{{trans('tela_dados_pessoais.tela_dados_pessoais')}}</a></li>
        @liberamenu(Auth()->user())
        <li class="{{ Route::currentRouteNamed('submeter.trabalho') ? 'active' : '' }}"><a href="{{ route('submeter.trabalho') }}">{{ trans('tela_dados_academicos.tela_dados_academicos') }}</a></li>
        @endliberamenu
        <li class="{{ Route::currentRouteNamed('auth.logout') ? 'active' : '' }}"><a href="{{ route('auth.logout') }}">{{ trans('tela_sair.sair') }}</a></li>
        @impersonating_candidato
          <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('impersonating').submit();">Voltar ao Admin</a>
          </li>

          {!! Form::open(array('route' => 'admin.impersonate', 'id' => 'impersonating', 'class' => 'hidden')) !!}
            {{ method_field('DELETE') }}
          {!! Form::close() !!}

        @endimpersonating_candidato
      </ul>
    </div>
  </nav>
</div>