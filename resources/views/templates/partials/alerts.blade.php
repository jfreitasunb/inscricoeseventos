@if (notify()->ready())
	<div class="alert alert-{{ notify()->type() }}">
		{{ notify()->message() }}
	</div>
@endif