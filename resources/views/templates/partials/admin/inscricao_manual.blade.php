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
            {!! Form::label('email', trans('tela_dados_pessoais.email'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('email', '', ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
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
        </fieldset>
        <fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_submeter_trabalho.categoria')}}</legend>
  <div class="col-md-6">
    @foreach ($categorias as $categoria)
      <label class="radio-inline">{!! Form::radio('id_categoria_participante', $categoria->id, '', ['required' => '']) !!}{{ " ".$categoria->participante_categoria }}</label>
    @endforeach
  </div>
</fieldset>

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_submeter_trabalho.participante_convidado')}}</legend>
  <div class="col-md-6">
      <label class="radio-inline"><input type="radio" name="participante_convidado" value="1">{{ trans('tela_submeter_trabalho.sim') }}</label>
      <label class="radio-inline"><input type="radio" name="participante_convidado" value="0">{{ trans('tela_submeter_trabalho.nao') }}</label>
  </div>
</fieldset>

<fieldset class="scheduler-border">
  <legend class="scheduler-border">{{trans('tela_submeter_trabalho.apresentar_trabalho')}}</legend>
  <div class="col-md-6">
      <label class="radio-inline"><input type="radio" name="apresentar_trabalho" value="on" onclick="javascript:yesnoCheck();" id="yesCheck">{{ trans('tela_submeter_trabalho.sim') }}</label>
      <label class="radio-inline"><input type="radio" name="apresentar_trabalho" value="off" onclick="javascript:yesnoCheck();" id="noCheck">{{ trans('tela_submeter_trabalho.nao') }}</label>
  </div>
</fieldset>

<div id="ifYes" style="display:none">
  <fieldset class="scheduler-border">
    <legend class="scheduler-border">{{trans('tela_submeter_trabalho.tipo_apresentacao')}}</legend>
    <div class="col-md-6">
      @foreach ($tipos_apresentacao as $tipo)
        <label class="radio-inline">{!! Form::radio('id_tipo_apresentacao', $tipo->id, '', []) !!}{{ " ".$tipo->nome_apresentacao }}</label>
      @endforeach
    </div>
  </fieldset>

  <fieldset class="scheduler-border">
    <legend class="scheduler-border">{{trans('tela_submeter_trabalho.area_trabalho')}}</legend>
    <div class="row">
      {!! Form::label('titulo_trabalho', trans('tela_submeter_trabalho.titulo_apresentacao'), ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-8">
      {!! Form::text('titulo_trabalho', '', ['class' => 'form-control input-md formhorizontal']) !!}
      </div>
    </div>

    <div class="row">
      {!! Form::label('autor_trabalho', trans('tela_submeter_trabalho.autores'), ['class' => 'col-md-4 control-label', ])!!}
      <div class="col-md-8">
      {!! Form::text('autor_trabalho','', ['class' => 'form-control input-md formhorizontal']) !!}
      </div>
    </div>

    <div class="row">
      {!! Form::label('abstract_trabalho', trans('tela_submeter_trabalho.abstract_text'), ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-8">
      {!! Form::textarea('abstract_trabalho', '', ['class' => 'form-control input-md formhorizontal', 'rows' => '10']) !!}
      </div>
    </div>
    @if (count($secao) ==1)
      {!! Form::hidden('id_area_trabalho', array_keys($secao)[0],  []) !!}
    @else
      <div class="row">
      {!! Form::label('id_area_trabalho', trans('tela_submeter_trabalho.secao'), ['class' => 'col-md-4 control-label'])!!}
      <div class="col-md-4">
      <label class="radio">{!! Form::select('id_area_trabalho', $secao, '',  ['class' => 'form-control col-md-6']) !!}</label>
      </div>
    </div>
    @endif
  </fieldset>
</div>

<div class="form-group">
  <div class="row">
    <div class="col-md-6 col-md-offset-3 text-center">
      {!! Form::submit('Registrar', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
    </div>
  </div>
</div>
{!! Form::close() !!}
@endsection

@section('scripts')
<script type="text/javascript">
    $('#id_pais').change(function(){
    var paisID = $(this).val();    
    if(paisID){
        $.ajax({
           type:"GET",
           url:"{{url('api/get-state-list')}}?country_id="+paisID,
           success:function(res){               
            if(res){
                $("#estado").empty();
                $("#estado").append('<option>Select</option>');
                $.each(res,function(key,value){
                    $("#estado").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#estado").empty();
            }
           }
        });
    }else{
        $("#estado").empty();
        $("#cidade").empty();
    }      
   });
    $('#estado').on('change',function(){
    var estadoID = $(this).val();    
    if(estadoID){
        $.ajax({
           type:"GET",
           url:"{{url('api/get-city-list')}}?state_id="+estadoID,
           success:function(res){               
            if(res){
                $("#cidade").empty();
                $.each(res,function(key,value){
                    $("#cidade").append('<option value="'+key+'">'+value+'</option>');
                });
           
            }else{
               $("#cidade").empty();
            }
           }
        });
    }else{
        $("#cidade").empty();
    }
        
   });
</script>
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
  {!! Html::script( asset('js/datepicker.js') ) !!}
  {!! Html::script( asset('js/parsley.min.js') ) !!}
  {!! Html::script( asset('js/show_hide.js') ) !!}

@endsection