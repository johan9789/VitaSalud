<?php
class Distrito extends Eloquent {
	protected $table = 'distrito';
	protected $primaryKey = 'iddistrito';
	public $timestamps = false;

	public function personas(){
		return $this->hasMany('Persona', 'idPersona');
	}

	public function proveedores(){
		return $this->hasMany('Proveedor', 'idPersona');
	}

	public function provincia(){
		return $this->belongsTo('Provincia', 'idprovincia');
	}

	/**
	 * Lista de distritos.
	 * 
	 * Utilizado en:
	 * Gestion\Usuarios\HomeController@getIndex
	 * Gestion\Usuarios\HomeController@postDistrito
	 * Gestion\Proveedores\Administrador\HomeController@index
	 * Gestion\Proveedores\Administrador\HomeController@show
	 * Compras\Administrador\HomeController@index
	 * Compras\Distribuidor\HomeController@index
	 * Ventas\HomeController@get_index
	 * 
	 */
	public function scopeLista($query){
		return $query->where('idprovincia', '=', 2048)
					->where('EstadoDistrito', '=', 1)
					->orderBy('NombreDistrito');
	}	

}