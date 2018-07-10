@if ($errors->count() > 0)
	<div id="ERRORS_COPY" style="display: none;" class="alert alert-danger" role="alert">
		<ul>
			@foreach ($errors->all() as $error)
				<li> {{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif