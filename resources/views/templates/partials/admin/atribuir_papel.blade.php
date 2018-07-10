@extends('templates.default')

@section('stylesheets')
  <link href="{{ asset('css/parsley.css') }}" rel="stylesheet">
@endsection

@section('ativa_conta')
  <div class="row">
    @if(count($dados_usuario))
    <form action="{{ route('atribuir.papel') }}" method="POST" data-parsley-validate class="form-horizontal">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">Mudar Papel</legend>
        
        <div class="form-group">
          <div class="row">
            <label class="col-md-2 control-label" for="codigo">E-mail:</label>  
            <div class="col-md-2">
              <input id="email" name="email" type="text" readonly="readonly" class="form-control input-md" value="{{$dados_usuario['email']}}">
            </div>
          </div>
          <div class="row">
            <label class="col-md-2 control-label" for="papel_atual">Papel Atual:</label>  
            <div class="col-md-2">
              <input id="papel_user" name="papel_user" type="text" readonly="readonly" class="form-control input-md" value="{{$dados_usuario['papel_atual']}}">
            </div>
          </div>
          <div class="row">
            <label class="col-md-2 control-label" for="papelnovo">Novo Papel:</label> 
            <div class="col-md-2">
            <select id="novo_papel" name="novo_papel" class="form-control" required="">
                <option value="" selected=""></option>
                @foreach ($papeis_disponiveis as $papel_novo)
                  <option value="{{$papel_novo}}">{{$papel_novo}}</option>
                @endforeach
              </select>
          </div>
          </div>
          @if ($errors->has('novo_papel'))
            <span class="help-block">{{ $errors->first('novo_papel') }}</span>
          @endif
        </div>
        <div class="col-xs-12" style="height:35px;"></div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
              <input type="submit" name="registrar" id="register-submit" class="btn btn-primary btn-lg" tabindex="4" value="Alterar Papel">
            </div>
          </div>
        </div>
      </fieldset>
    @else
    <form action="{{ route('pesquisar.papel') }}" method="POST" data-parsley-validate class="form-horizontal">
      <fieldset class="scheduler-border">
        <legend class="scheduler-border">Pesquisar usuário</legend>
        
        <div class="form-group" {{ $errors->has('codigo') ? ' has-error' : '' }}>
          <div class="row">
            <label class="col-md-2 control-label" for="codigo">E-mail</label>  
            <div class="col-md-2">
              <input id="email" name="email" type="text" class="form-control input-md" required="" data-parsley-type="email" value="{{Request::old('email') ?: '' }}">
            </div>
          </div>
          @if ($errors->has('email'))
            <span class="help-block">{{ $errors->first('email') }}</span>
          @endif
        </div>



        <div class="col-xs-12" style="height:35px;"></div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-6 col-md-offset-3 text-center">
              <input type="submit" name="registrar" id="register-submit" class="btn btn-primary btn-lg" tabindex="4" value="Pesquisar">
            </div>
          </div>
        </div>
      </fieldset>
    @endif
      <input type="hidden" name="_token" value="{{ Session::token() }}">
    </form>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script src="{{ asset('i18n/pt-br.js') }}"></script>
@endsection
