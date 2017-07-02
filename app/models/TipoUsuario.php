<?php
class TipoUsuario extends Eloquent {
	protected $table = 'tipousuario';
	protected $primaryKey = 'id_tipousuario';
	public $timestamps = false;

	public function usuario(){
		return $this->hasMany('Usuario', 'idUsuario');
	}

	public function accesos(){
		return $this->belongsToMany('Acceso', 'acceso_tipousuario', 'id_tipousuario','id_acceso');
	}

}