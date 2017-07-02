<?php
use Illuminate\Auth\UserInterface;

class Usuario extends Eloquent implements UserInterface {
	protected $table = 'usuarios';
	protected $primaryKey = 'idUsuario';
	public $timestamps = false;

	public function tipoUsuario(){
		return $this->belongsTo('TipoUsuario', 'id_tipousuario');
	}

	public function persona(){
		return $this->belongsTo('Persona', 'idPersona');
	}
	
    public function getAuthIdentifier(){
        return $this->getKey();
    }

    public function getAuthPassword(){
        return $this->Password;
    }

    public function getRememberToken(){} 

    public function setRememberToken($value){}

    public function getRememberTokenName(){}

}