@extends('templates.default')

@section('stylesheets')
  {!! Html::style( asset('css/parsley.css') ) !!}
  {!! Html::style( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ) !!}
  {!! Html::style( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection

@section('admin_impersonate')
{!! Form::open(array('route' => 'admin.impersonate', 'class' => 'form-horizontal', 'data-parsley-validate' => '' )) !!}
<div class="form-group">
	{!! Form::label('email', 'Entre como o e-mail', ['class' => 'col-md-4 control-label']) !!}
	<div class="col-md-4">
		{!! Form::text('email', '' , ['class' => 'form-control input-md']) !!}
	</div>
</div>

<div class="form-group">
	<div class="row">
	    <div class="col-md-6 col-md-offset-3 text-center">
	      {!! Form::submit('Logar como', ['class' => 'btn btn-primary btn-lg register-submit']) !!}
	    </div>
	  </div>
	</div>
</div>
{!! Form::close() !!}

@endsection

@section('scripts')
  {!! Html::script( asset('bower_components/moment/min/moment.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/pt-br.js') ) !!}
  {!! Html::script( asset('bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ) !!}
  {!! Html::script( asset('bower_components/moment/locale/fr.js') ) !!}
@endsection