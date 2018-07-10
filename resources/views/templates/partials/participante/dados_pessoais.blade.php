@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('dados_pessoais')
<div class="row">
  @if ($editar_dados)
    {!! Form::open(array('route' => 'dados.pessoais.salvar', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
  @else
    {!! Form::open(array('route' => 'dados.pessoais.editar', 'class' => 'form-horizontal', 'method' => 'GET')) !!}
  @endif
    <fieldset class="scheduler-border">
      <legend class="scheduler-border">{{ trans('tela_dados_pessoais.tela_dados_pessoais') }}</legend>
        
        @if ($editar_dados)
          <div class="row">
            {!! Form::label('nome', trans('tela_dados_pessoais.nome'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('nome', $dados['nome'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
            </div>
          </div>
        @else
          <div class="row">
            {!! Form::label('nome', trans('tela_dados_pessoais.nome'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('nome', $dados['nome'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
            </div>
          </div>
        @endif
        
        @if ($editar_dados)
          <div class="row">
            {!! Form::label('data_nascimento', trans('tela_dados_pessoais.data_nascimento'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('data_nascimento', $dados['data_nascimento'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
            </div>
          </div>
        @else
          <div class="row">
            {!! Form::label('nascimento', trans('tela_dados_pessoais.data_nascimento'), ['class' => 'col-md-4 control-label'])!!}
            <div class="col-md-4">
              {!! Form::text('nascimento', $dados['data_nascimento'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
            </div>
          </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('numerorg', trans('tela_dados_pessoais.rg'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('numerorg', $dados['numerorg'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('numerorg', trans('tela_dados_pessoais.rg'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('numerorg', $dados['numerorg'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('endereco', trans('tela_dados_pessoais.endereco'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('endereco', $dados['endereco'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('endereco', trans('tela_dados_pessoais.endereco'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('endereco', $dados['endereco'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('cep', trans('tela_dados_pessoais.cep'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('cep', $dados['cep'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'required' => '']) !!}
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('cep', trans('tela_dados_pessoais.cep'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('cep', $dados['cep'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('pais', trans('tela_dados_pessoais.pais'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::select('pais', ['' => 'Select'] +$countries,'',array('class'=>'form-control input-md formhorizontal','id'=>'pais'));!!}
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('pais', trans('tela_dados_pessoais.pais'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('pais', $dados['pais'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('estado', trans('tela_dados_pessoais.estado'), ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-4">
            <select name="estado" id="estado" class="form-control  input-md formhorizontal" ></select>
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('estado', trans('tela_dados_pessoais.estado'), ['class' => 'col-md-4 control-label']) !!}
          <div class="col-md-4">
            {!! Form::text('pais', $dados['estado'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        

        @if ($editar_dados)
          <div class="row">
          {!! Form::label('cidade', trans('tela_dados_pessoais.cidade'), ['class' => 'col-md-4 control-label', 'required' => '']) !!}
          <div class="col-md-4">
            <select name="cidade" id="cidade" class="form-control input-md formhorizontal"></select>
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('cidade', trans('tela_dados_pessoais.cidade'), ['class' => 'col-md-4 control-label', 'required' => '']) !!}
          <div class="col-md-4">
            {!! Form::text('pais', $dados['cidade'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        
        
        @if ($editar_dados)
          <div class="row">
          {!! Form::label('celular', trans('tela_dados_pessoais.celular'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('celular', $dados['celular'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'placeholder' => '(DD)#######']) !!}
          </div>
        </div>
        @else
          <div class="row">
          {!! Form::label('celular', trans('tela_dados_pessoais.celular'), ['class' => 'col-md-4 control-label'])!!}
          <div class="col-md-4">
            {!! Form::text('celular', $dados['celular'] ?: '' , ['class' => 'form-control input-md formhorizontal', 'disabled' => 'disabled']) !!}
          </div>
        </div>
        @endif
        
        
        @if ($editar_dados)
          <div class="form-group">
            <div class="row">
              <div class="col-md-6 col-md-offset-3 text-center">
                {!! Form::submit(trans('tela_dados_pessoais.menu_enviar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
              </div>
            </div>
          </div>
        @else
          <div class="form-group">
            <div class="row">
              <div class="col-md-6 col-md-offset-3 text-center">
                {!! Form::submit(trans('tela_dados_pessoais.menu_editar'), ['class' => 'btn btn-primary btn-lg register-submit']) !!}
              </div>
            </div>
          </div>
        @endif
      {!! Form::close() !!}
    </fieldset>
</div>
@endsection

@section('scripts')
  <script src="{{ asset('js/parsley.min.js') }}"></script>
  <script src="{{ asset('i18n/pt-br.js') }}"></script>
@endsection

@section('post-script')
<script type="text/javascript">
    $('#pais').change(function(){
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
@endsection