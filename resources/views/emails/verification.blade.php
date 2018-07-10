<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
</head>
<body>

Olá {{ $user->nome }},

<a href="{{ url('register/verify/'.$user->validation_code) }}" target="_blank">{{ url('register/verify/'.$user->validation_code) }}</a>


</body>
</html>