<?php
return array(
	'usuario_log' => (Session::has('usuario')) ? Persona::find(Session::get('id_persona')) : [],
	'distritos' => Distrito::select()->where('idprovincia', '=', 2048)->where('EstadoDistrito', '=', 1)->lists('NombreDistrito', 'iddistrito'),
);