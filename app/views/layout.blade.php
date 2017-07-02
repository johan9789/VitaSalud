<!DOCTYPE html>
<html class="no-js">
<head>
<meta charset="UTF-8">
<title>Sistema de Gestión - VitaSalud</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--<base href="{{ URL::to('').'/' }}" target="">-->
{{ HTML::style('assets/lib/bootstrap/css/bootstrap.min.css') }}
<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="http://arthurgouveia.com/prettyCheckable/js/prettyCheckable/dist/prettyCheckable.css">
{{ HTML::style('assets/css/main.min.css') }}
{{ HTML::style('assets/lib/fullcalendar/fullcalendar.css') }}
<script>
less = {
    env: "development",
    relativeUrls: false,
    rootpath: "{{ URL::to('assets') }}"
};
</script>
{{ HTML::style('assets/css/style-switcher.css') }}
{{ HTML::style('assets/css/less/theme.less', ['rel' => 'stylesheet/less']) }}
{{ HTML::script('assets/lib/less/less-1.7.3.min.js') }}
{{ HTML::script('assets/lib/modernizr/modernizr.min.js') }}    
{{ HTML::style('assets/lib/datatables/3/dataTables.bootstrap.css') }}
{{ HTML::style('assets/own/css/pagination.css') }}
{{ HTML::style('assets/own/css/contenedor.css') }}
{{ HTML::style('assets/lib/jquery.uniform/themes/default/css/uniform.default.css') }}
{{ HTML::style('assets/lib/inputlimiter/jquery.inputlimiter.css') }}
{{ HTML::style('assets/lib/chosen/chosen.min.css') }}
{{ HTML::style('assets/lib/colorpicker/css/colorpicker.css') }}
{{ HTML::style('assets/css/colorpicker_hack.css') }}
{{ HTML::style('assets/lib/tagsinput/jquery.tagsinput.css') }}
{{ HTML::style('assets/lib/daterangepicker/daterangepicker-bs3.css') }}
{{ HTML::style('assets/lib/datepicker/css/datepicker.css') }}
{{ HTML::style('assets/lib/timepicker/css/bootstrap-timepicker.min.css') }}
{{ HTML::style('assets/lib/switch/css/bootstrap3/bootstrap-switch.min.css') }}
{{ HTML::style('assets/lib/jasny-bootstrap/css/jasny-bootstrap.min.css') }}
{{ HTML::style('assets/css/assets/css/less/theme.less') }}
<style type="text/css">
#foto_usuario:hover {
    width: 100px;
    border-radius: 50%;
}
#foto_cambiar:hover {
    width: 330px;
}
</style> 
</head>
<body>

<div class="bg-dark dk" id="wrap">
    <div id="top">
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container-fluid">
                <header class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Navegación</span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                        <span class="icon-bar"></span> 
                    </button>
                    <a href="{{ URL::to('') }}" class="navbar-brand"> {{ HTML::image('assets/img/logo.png', '') }} </a> 
                </header>
                <div class="topnav">
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Fullscreen" data-toggle="tooltip" class="btn btn-default btn-sm" id="toggleFullScreen"><i class="glyphicon glyphicon-fullscreen"></i></a> 
                    </div>
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="E-mail" data-toggle="tooltip" class="btn btn-default btn-sm">
                            <i class="fa fa-envelope"></i>
                            <span class="label label-warning">5</span> 
                        </a> 
                        <a data-placement="bottom" data-original-title="Messages" href="#" data-toggle="tooltip" class="btn btn-default btn-sm">
                            <i class="fa fa-comments"></i>
                            <span class="label label-danger">4</span> 
                        </a>  
                        <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-default btn-sm" href="#helpModal">
                            <i class="fa fa-question"></i>
                        </a> 
                    </div>
                    @if(Session::has('usuario'))
                    <div class="btn-group">
                        <a href="{{ URL::to('logout') }}" data-toggle="tooltip" data-original-title="Salir" data-placement="bottom" class="btn btn-metis-1 btn-sm"> 
                            <i class="fa fa-power-off"></i>
                        </a> 
                    </div>
                    @endif
                    <div class="btn-group">
                        <a data-placement="bottom" data-original-title="Ocultar/Mostrar lado izquierdo" data-toggle="tooltip" class="btn btn-primary btn-sm toggle-left" id="menu-toggle"> 
                            <i class="fa fa-bars"></i>
                        </a> 
                        <a data-placement="bottom" data-original-title="Ocultar/Mostrar lado derecho" data-toggle="tooltip" class="btn btn-default btn-sm toggle-right"> <span class="glyphicon glyphicon-comment"></span> </a>
                    </div>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <!-- .nav -->
                    <ul class="nav navbar-nav">                        
                        <li @if(Request::url() === URL::to('')) {{ 'class="active"' }} @endif> {{ HTML::link('', 'Inicio') }} </li>
                        <li @if(Request::url() === URL::to('productos/catalogo') || Request::url() === URL::to('gestion-productos/catalogo')) {{ 'class="active"' }} @endif> {{ HTML::link('productos/catalogo', 'Productos') }} </li>
                        @if(Session::has('usuario'))                        
                        @if(Session::get('tipo_usuario') == 'Distribuidor')
                        <!--<li @if(Request::url() === URL::to('mi-cuenta')) {{ 'class="active"' }} @endif> {{ HTML::link('mi-cuenta', 'Mi Cuenta', ['style' => 'cursor: not-allowed;'])}} </li>-->
                        <!-- <li @if(Request::url() === URL::to('mi-oficina')) {{ 'class="active"' }} @endif> {{ HTML::link('mi-oficina', 'Mi Oficina', ['style' => 'cursor: not-allowed;']) }} </li> -->
                        <li @if(Request::url() === URL::to('pedidos/dist/realizar')) {{ 'class="active"' }} @endif> {{ HTML::link('pedidos/dist/realizar', 'Realizar Pedidos') }}  </li>
                        @endif
                        @endif
                    </ul><!-- /.nav -->
                </div>
            </div><!-- /.container-fluid -->
        </nav><!-- /.navbar -->
        <header class="head">
            <div class="search-bar">
                <form class="main-search" action="">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Buscar...">
                        <span class="input-group-btn">
                            <button class="btn btn-primary btn-sm text-muted" type="button"> 
                                <i class="fa fa-search"></i> 
                            </button>
                        </span> 
                    </div>
                </form><!-- /.main-search -->
            </div><!-- /.search-bar -->
            <div class="main-bar">
                <h3>
                    <i class="fa fa-desktop"></i>
                    &nbsp; 
                    @if(isset($title)) 
                    {{ $title }}  
                    @else 
                    Escritorio 
                    @endif
                </h3>
            </div><!-- /.main-bar -->
        </header><!-- /.head -->
    </div><!-- /#top -->
    <div id="left">
        <div class="media user-media bg-dark dker">
            <div class="user-media-toggleHover"> <span class="fa fa-user"></span> </div>
            <div class="user-wrapper bg-dark">
                @if(Session::has('usuario'))    
                <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="user-link" href="#change_photo_modal" id="">
                    @if(empty(Session::get('user_model')->persona->foto))
                    {{ HTML::image('assets/user_photos/no_photo.jpg', 'User Picture', ['class' => 'media-object img-thumbnail user-img', 'width' => 90, 'id' => 'foto_usuario']) }}
                    @else
                    {{ HTML::image('assets/user_photos/'.Session::get('user_model')->persona->foto, 'User Picture', ['class' => 'media-object img-thumbnail user-img', 'width' => 90, 'id' => 'foto_usuario']) }}
                    @endif
                    <span class="label label-default user-label" style="font-size:100%">
                        <i class="glyphicon glyphicon-camera"></i>
                    </span>                    
                </a>
                <div class="media-body">
                    <h5 class="media-heading">{{ Session::get('user_model')->persona->nombre_completo }}</h5>
                    <ul class="list-unstyled user-info">
                        <li><i>{{ Session::get('user_model')->tipoUsuario->nombretipo }}  </i></li>
                        <li>
                            <a data-toggle="modal" data-original-title="Help" data-placement="bottom" class="btn btn-success btn-xs btn-round" href="#edit_user_modal" id="ed_per">
                                &nbsp;&nbsp;Editar perfil&nbsp;&nbsp;
                            </a>
                        </li>
                        <li>
                            <small id="current_time">
                                <?php date_default_timezone_set('America/Lima'); ?>
                                <i class="fa fa-calendar"></i>&nbsp; {{ Date::current('d/m/Y H:i') }}
                            </small> 
                        </li>
                    </ul>                    
                </div>
                @endif
            </div>
        </div>
        <div id="div_accesos">
            <div id="div_accesos_act">
                @include('accesos')
            </div>
        </div>
    </div><!-- /#left -->
    <div id="content">
        <div class="outer">
            <div id="contenedor_central" class="inner bg-light lter"> 
                @yield('body')
            </div><!-- /.inner -->      
        </div><!-- /.outer -->
    </div><!-- /#content -->
    <div id="right" class="bg-light lter">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Warning!</strong>  Best check yo self, you're not looking too good.
        </div>
        <!-- .well well-small -->
        <div class="well well-small dark">
            <ul class="list-unstyled">
                <li>Visitor <span class="inlinesparkline pull-right">1,4,4,7,5,9,10</span> </li>
                <li>Online Visitor <span class="dynamicsparkline pull-right">Loading..</span> </li>
                <li>Popularity <span class="dynamicbar pull-right">Loading..</span> </li>
                <li>New Users <span class="inlinebar pull-right">1,3,4,5,3,5</span> </li>
            </ul>
        </div><!-- /.well well-small -->
        <!-- .well well-small -->
        <div class="well well-small dark">
            <button class="btn btn-block">Default</button>
            <button class="btn btn-primary btn-block">Primary</button>
            <button class="btn btn-info btn-block">Info</button>
            <button class="btn btn-success btn-block">Success</button>
            <button class="btn btn-danger btn-block">Danger</button>
            <button class="btn btn-warning btn-block">Warning</button>
            <button class="btn btn-inverse btn-block">Inverse</button>
            <button class="btn btn-metis-1 btn-block">btn-metis-1</button>
            <button class="btn btn-metis-2 btn-block">btn-metis-2</button>
            <button class="btn btn-metis-3 btn-block">btn-metis-3</button>
            <button class="btn btn-metis-4 btn-block">btn-metis-4</button>
            <button class="btn btn-metis-5 btn-block">btn-metis-5</button>
            <button class="btn btn-metis-6 btn-block">btn-metis-6</button>
        </div><!-- /.well well-small -->
        <!-- .well well-small -->
        <div class="well well-small dark">
            <span>Default</span> 
            <span class="pull-right"><small>20%</small> </span> 
            <div class="progress xs"><div class="progress-bar progress-bar-info" style="width: 20%"></div></div>
            <span>Success</span> 
            <span class="pull-right"><small>40%</small> </span> 
            <div class="progress xs"><div class="progress-bar progress-bar-success" style="width: 40%"></div></div>
            <span>warning</span> 
            <span class="pull-right"><small>60%</small> </span> 
            <div class="progress xs"><div class="progress-bar progress-bar-warning" style="width: 60%"></div></div>
            <span>Danger</span> 
            <span class="pull-right"><small>80%</small> </span> 
            <div class="progress xs"><div class="progress-bar progress-bar-danger" style="width: 80%"></div></div>
        </div>
    </div><!-- /#right -->
</div><!-- /#wrap -->

<footer class="Footer bg-dark dker">
    <p>{{ date('Y') }} &copy; VitaSalud</p>
</footer><!-- /#footer -->

<!-- #helpModal -->
<div id="helpModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Modal title</h4>
            </div>
            <div class="modal-body">
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                    in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- end #helpModal -->

@if(Session::has('usuario'))
<!-- #editUserModal -->
<div id="edit_user_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">
                    Editar perfil 
                    <a data-toggle="modal" data-original-title="Help" data-placement="bottom" style="text-decoration: none;" href="#change_password_modal">
                        &nbsp;&nbsp;¿Cambiar Contraseña?&nbsp;&nbsp;
                    </a>
                </h4>                
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'perfil/editar', 'class' => 'form-vertical', 'id' => 'form_editar_perfil']) }}
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Nombre</label>
                            <div class="col-lg-8">
                                {{ Form::text('nombre', Session::get('user_model')->persona->Nombres, ['readonly', 'class' => 'form-control', 'style' => 'background: transparent; border: 0 none; cursor: default;']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Apellidos</label>
                            <div class="col-lg-8">
                                {{ Form::text('apellidos', Session::get('user_model')->persona->Apellidos, ['readonly', 'class' => 'form-control', 'style' => 'background: transparent; border: 0 none; cursor: default;']) }}
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Teléfono</label>
                            <div class="col-lg-8">
                                {{ Form::text('telefono', Session::get('user_model')->persona->Telefono, ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Celular</label>
                            <div class="col-lg-8">
                                {{ Form::text('celular', Session::get('user_model')->persona->Celular, ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">E-mail</label>
                            <div class="col-lg-8">
                                {{ Form::email('email', Session::get('user_model')->persona->email, ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Dirección</label>
                            <div class="col-lg-8">
                                {{ Form::text('direccion', Session::get('user_model')->persona->Direccion, ['required', 'class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Ciudad</label>
                            <div class="col-lg-8">
                                <select name="distrito" required="required" class="form-control">
                                    @foreach(Config::get('general.distritos') as $key => $value)
                                    <option value="{{ $key }}" @if($key == Session::get('user_model')->persona->iddistrito) {{ 'selected="selected"' }} @endif>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-actions no-margin-bottom" style="margin-left: 460px;">
                            {{ Form::submit('Actualizar', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- end #helpModal -->

<!-- #change_password:modal -->
<div id="change_password_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cambiar contraseña</h4>                
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    {{ Form::open(['url' => 'perfil/contrasena', 'class' => 'form-vertical', 'id' => 'form_cambiar_pass']) }}
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Contraseña actual</label>
                            <div class="col-lg-8">
                                {{ Form::password('contrasena_actual', ['required', 'class' => 'form-control', 'id' => 'pass_act']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Contraseña nueva</label>
                            <div class="col-lg-8">
                                {{ Form::password('contrasena_nueva', ['required', 'class' => 'form-control', 'id' => 'pass_new']) }}
                            </div>
                        </div>                    
                        <div class="form-group">
                            <label for="text1" class="control-label col-lg-4">Confirmar contraseña</label>
                            <div class="col-lg-8">
                                {{ Form::password('confirmar_contrasena', ['required', 'class' => 'form-control', 'id' => 'cf_pass']) }}
                            </div>
                        </div>                    
                        <div class="form-actions no-margin-bottom">
                            {{ Form::submit('Actualizar', ['class' => 'btn btn-primary']) }}
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- end #helpModal -->

<!-- #change_photo_modal -->
<div id="change_photo_modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Cambiar foto de perfil</h4>
            </div>
            <div class="modal-body">
                <div id="div-1" class="body">
                    <center>
                        {{ Form::open(['url' => 'perfil/imagen', 'files' => true, 'class' => 'form-vertical', 'id' => 'form_cambiar_foto']) }}
                            <div class="form-group">
                                @if(Session::get('user_model')->persona->foto == '')
                                {{ HTML::image('assets/user_photos/no_photo.jpg', 'User Picture', ['class' => 'media-object img-thumbnail user-img', 'width' => 300, 'style' => 'border-radius: 50%', 'id' => 'foto_cambiar']) }}
                                @else
                                {{ HTML::image('assets/user_photos/'.Session::get('user_model')->persona->foto, 'User Picture', ['class' => 'media-object img-thumbnail user-img', 'width' => 300, 'style' => 'border-radius: 50%', 'id' => 'foto_cambiar']) }}
                                @endif
                                <br>
                                {{ Form::file('foto', ['required', 'id' => 'file_XD', 'title' => 'Escoger nueva foto', 'data-filename-placement' => 'inside']) }}
                                <span id="url_foto_XD" url="{{ URL::to('assets/user_photos/'.Session::get('user_model')->persona->foto) }}"></span>
                            </div>  
                            <div class="form-actions no-margin-bottom">
                                {{ Form::button('Cambiar foto', ['class' => 'btn btn-primary', 'id' => 'btn_cambiar_foto']) }}
                            </div>
                        {{ Form::close() }}
                    </center>
                </div>
            </div>
            <br>
            <div class="modal-footer"></div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- end #helpModal -->
@endif

{{ Form::hidden('', Request::url(), ['id' => 'url_actual']) }}
{{ Form::hidden('', URL::to(''), ['id' => 'generar_url']) }}

{{ HTML::script('assets/lib/jquery/jquery.min.js') }}
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
{{ HTML::script('assets/own/js/layout.js') }}
{{ HTML::style('assets/own/css/apprise.css') }}
{{ HTML::style('assets/own/css/jquery-ui-1.8.17.custom.css') }}
{{ HTML::script('assets/own/js/resources/jquery-ui-1.8.9.custom.min.js') }}
{{ HTML::script('assets/own/js/resources/apprise-1.5.full.js') }}
{{ HTML::script('assets/lib/datatables/jquery.dataTables.js') }}
{{ HTML::script('assets/lib/datatables/3/dataTables.bootstrap.js') }}
{{ HTML::script('assets/lib/bootstrap/js/bootstrap.min.js') }}
{{ HTML::script('assets/own/js/resources/bootstrap.file-input.js') }}
<script type="text/javascript" src="http://arthurgouveia.com/prettyCheckable/js/prettyCheckable/dev/prettyCheckable.js"></script>
@yield('resources')

{{ HTML::script('assets/lib/screenfull/screenfull.js') }}
{{ HTML::script('assets/lib/moment/moment.min.js') }}
{{ HTML::script('assets/lib/fullcalendar/fullcalendar.min.js') }}
{{ HTML::script('assets/lib/jquery.tablesorter/jquery.tablesorter.min.js') }}
{{ HTML::script('assets/lib/jquery.sparkline/jquery.sparkline.min.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.selection.js') }}
{{ HTML::script('assets/lib/flot/jquery.flot.resize.js') }}
{{ HTML::script('assets/js/core.js') }}
{{ HTML::script('assets/js/app.min.js') }}

{{ HTML::script('assets/lib/jquery.uniform/jquery.uniform.min.js') }}
{{ HTML::script('assets/lib/inputlimiter/jquery.inputlimiter.js') }}
{{ HTML::script('assets/lib/chosen/chosen.jquery.min.js') }}
{{ HTML::script('assets/lib/colorpicker/js/bootstrap-colorpicker.js') }}
{{ HTML::script('assets/lib/tagsinput/jquery.tagsinput.js') }}
{{ HTML::script('assets/lib/validVal/js/jquery.validVal.min.js') }}
{{ HTML::script('assets/lib/daterangepicker/daterangepicker.js') }}
{{ HTML::script('assets/lib/datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('assets/lib/timepicker/js/bootstrap-timepicker.min.js') }}
{{ HTML::script('assets/lib/switch/js/bootstrap-switch.min.js') }}
{{ HTML::script('assets/lib/autosize/jquery.autosize.min.js') }}
{{ HTML::script('assets/lib/jasny-bootstrap/js/jasny-bootstrap.min.js') }}
<script type="text/javascript">
$('div#contenedor_central').css('min-height', ($(document).height()) - 178);
$(function(){
    Metis.dashboard();
});
</script>
{{ HTML::script('assets/js/style-switcher.js') }}

@if(Session::has('mensaje'))
<script type="text/javascript">
apprise('{{ Session::get('mensaje') }}', {'animate':true});
</script>
@endif

</body>
</html>