<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión - VitaSalud</title>
<meta name="msapplication-TileColor" content="#5bc0de">
<meta name="msapplication-TileImage" content="assets/img/metis-tile.png">
{{ HTML::style('assets/lib/bootstrap/css/bootstrap.min.css') }}
{{ HTML::style('assets/lib/font-awesome/css/font-awesome.min.css') }}
{{ HTML::style('assets/css/main.min.css') }}
{{ HTML::style('assets/lib/animate.css/animate.min.css') }}
</head>
<body class="login">

<div class="form-signin">
  	<div class="text-center"> {{ HTML::image('assets/img/logo.png', 'Metis Logo') }} </div>
  	<hr>
  	<div class="tab-content">
        <div id="login" class="tab-pane active">
            {{ Form::open(['url' => 'login']) }}
                <p class="text-muted text-center"> Ingresa tu usuario y contraseña </p>
                {{ Form::text('usuario', '', ['required', 'placeholder' => 'Usuario', 'class' => 'form-control top', 'autofocus']) }}
                {{ Form::password('contrasena', ['required', 'placeholder' => 'Contraseña', 'class' => 'form-control bottom']) }}
                @if(Session::has('mensaje'))
                <p class="text-muted text-center" style="color: red;">{{{ Session::get('mensaje') }}}</p>
                @endif
                {{ Form::submit('Ingresar', ['class' => 'btn btn-lg btn-primary btn-block']) }}
            {{ Form::close() }}
        </div>     
    </div>   
</div>

{{ HTML::script('assets/lib/jquery/jquery.min.js') }}
{{ HTML::script('assets/lib/bootstrap/js/bootstrap.min.js') }}

</body>
</html>