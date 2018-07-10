@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('ativa_conta')
@if ($modo_pesquisa)
  {!! Form::open(array('route' => 'pesquisa.usuario', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
@else
  {!! Form::open(array('route' => 'altera.ativa.conta', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!} 
@endif

<div class="form-group">
  {!! Form::label('campo_pesquisa', 'Pesquisar Usuário', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-4">
    {!! Form::text('campo_pesquisa', '' , ['class' => 'form-control input-md']) !!}
  </div>
</div>
<div class="form-group">
  {!! Form::label('tipo_pesquisa', 'Pesquisar por:', ['class' => 'col-md-4 control-label']) !!}
  <div class="col-md-4">
    @foreach($tipo_pesquisa as $tipo => $key)
        <div class="col-md-4">
          <label class="radio-inline">{!! Form::radio('tipo_pesquisa', $tipo, ['required' => '']) !!} {!! $key !!}</label>
        </div>
    @endforeach
  </div>
</div>
@if (!$modo_pesquisa)
<hr>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th class="col-md-3" scope="col">Nome</th>
          <th class="col-md-3" scope="col">E-mail</th>
          <th class="col-sm-1" scope="col">Locale</th>
          <th class="col-sm-1" scope="col">Tipo</th>
          <th class="col-sm-1" scope="col">Ativo?</th>
          <th class="col-sm-1" scope="col">Ação</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          {!! Form::open(array('route' => 'altera.ativa.conta', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
          {!! Form::hidden('id_user', $user['id_user'], []) !!}
          <tr>
            <td>{!! Form::text('nome', $user['nome'], ['class' => 'form-control input-md']) !!}</td>
            <td>{!! Form::text('email', $user['email'], ['class' => 'form-control input-md']) !!}</td>
            <td>{!! Form::text('locale', $user['locale'], ['class' => 'form-control']) !!}</td>
            <td>{!! Form::text('user_type', $user['user_type'], ['class' => 'form-control input-md']) !!}</td>
            <td>{!! Form::text('ativo', $user['ativo'] ? 'Sim' : 'Não', ['class' => 'form-control input-md']) !!}</td>
            <td>{!! Form::submit('Alterar', ['class' => 'btn btn-warning']) !!}</td>
          </tr>
          {!! Form::close() !!}
        @endforeach
      </tbody>
    </table>
  </div>
@endif

@if ($modo_pesquisa)
  <div class="form-group">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        {!! Form::submit('Pesquisar', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
      </div>
    </div>
  </div> 
@else
  <div class="form-group">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 text-center">
        {!! Form::submit('Cancelar', ['class' => 'btn btn-primary btn-lg register-submit', 'name' =>'cancelar',]) !!}
      </div>
    </div>
  </div>
@endif

{!! Form::close() !!}
@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {{-- {!! Html::script( asset('js/datepicker.js') ) !!} --}}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection