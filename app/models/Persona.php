<?php
class Persona extends Eloquent {
	protected $table = 'persona';
	protected $primaryKey = 'idPersona';
	public $timestamps = false;	
	
	public function distrito(){
		return $this->belongsTo('Distrito', 'idPersona');
	}

	public function usuario(){
		return $this->hasOne('Usuario', 'idPersona');
	}

	public function clientes(){
		return $this->hasMany('Cliente', 'idPersona');
	}

	public function getNombreCompletoAttribute(){
		return $this->Apellidos.', '.$this->Nombres;
	}

	/**
	 * Lista de usuarios en el sistema.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\HomeController@getIndex
	 * 
	 */
	public function scopeLista($query, $usuario){
		return $query->join('usuarios', 'persona.idPersona', '=', 'usuarios.idPersona')
					->join('tipousuario', 'usuarios.id_tipousuario', '=', 'tipousuario.id_tipousuario')
					->where('persona.estado', '=', 1)
					->where('usuarios.Usuario', '!=', $usuario)
					->orderBy('persona.apellidos');
	}	

	/**
	 * Persona con detalles.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\HomeController@postEditar
	 * 
	 */
	public function scopeById($query, $idPersona){
		return $query->join('usuarios', 'persona.idPersona', '=', 'usuarios.idPersona')
					->join('tipousuario', 'usuarios.id_tipousuario', '=', 'tipousuario.id_tipousuario')
					->where('persona.idPersona', '=', $idPersona)
					->where('persona.estado', '=', 1)
					->orderBy('persona.apellidos')
					->get()[0];
	}

}