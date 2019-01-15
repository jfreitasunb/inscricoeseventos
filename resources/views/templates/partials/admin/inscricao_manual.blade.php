@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('inscricao_manual')

<fieldset class="scheduler-border">
  <legend class="scheduler-border">Realizar inscrição manualmente</legend>

  <div class="row">
  
    {!! Form::open(array('route' => 'inscricoes.manual', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}

        
        
          <div class="row">
            {!! Form::label('nome', trans('tela_dados_pessoais.nome'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('nome', '', ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
            </div>
          </div>
        

        
          <div class="row">
            {!! Form::label('nome_cracha', trans('tela_dados_pessoais.nome_cracha'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('nome_cracha', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
            </div>
          </div>
        

        
          <div class="row">
          {!! Form::label('numero_documento', trans('tela_dados_pessoais.numero_documento'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('numero_documento', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
          </div>
        </div>
        
        

        
          <div class="row">
          {!! Form::label('instituicao', trans('tela_dados_pessoais.instituicao'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('instituicao', '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
          </div>
        </div>
        
        
        
          <div class="row">
          {!! Form::label('id_pais', trans('tela_dados_pessoais.pais'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::select('id_pais', ['' => 'Select'] +$countries,'',array('class'=>'form-control input-md formhorizontal','id'=>'pais'));!!}
          </div>
        </div>
        
        
        
          <div class="form-group">
            <div class="row">
              <div class="col-md-6 col-md-offset-3 text-center">
                {!! Form::submit(trans('tela_dados_pessoais.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
              </div>
            </div>
          </div>
        
      {!! Form::close() !!}
    </fieldset>
</div>
</fieldset>

@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
@endsection