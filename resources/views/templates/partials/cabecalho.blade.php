<header id="header">
  <div class="header-main">
    <div class="header-main-content">
      <div class="container-fluid">
        <div class="row-fluid">
              <div class="col-sm-4 col-md-3 col-xs-12 image-container">
                <a href="{{URL::to('/')}}"> <img src="{{ asset('imagens/logo/logo_unb.png') }}" class="img-responsive" style="height:120px" /></a>
              </div>
              <div class="col-sm-8 col-md-7 col-xs-12">  
                <h1>{{ trans('mensagens_gerais.departamento') }}</h1>
                <h2>{{ trans('mensagens_gerais.inscricao_pos') }} {{ $ano_evento }}</h2>
                <h3>{{$periodo_inscricao}}</h3>
              </div>
        </div>
      </div>
    </div>
  </div>
</header>
