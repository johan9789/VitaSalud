<?php
class Empresa extends Eloquent {
	protected $table = 'empresa';
	protected $primaryKey = 'idEmpresa';
	public $timestamps = false;

	public function clientes(){
		return $this->hasMany('Cliente', 'idEmpresa');
	}

	/**
	 * Lista de empresas.
	 *
	 * Utilizado en:
	 * Ventas\HomeController@getIndex
	 *
	 */
	public function scopeLista($query){
		return $query->join('cliente', 'empresa.idEmpresa', '=', 'cliente.idEmpresa')
						->join('distrito', 'empresa.idDistrito', '=', 'distrito.idDistrito')
						->orderBy('empresa.NombreEmpresa');
	}

	/**
	 * Detalle de una empresa.
	 *
	 * Utilizado en:
	 * Resources\EmpresasController@show
	 *
	 */
	public function scopeDetalle($query, $id_empresa){
		return $query->join('cliente', 'empresa.idEmpresa', '=', 'cliente.idEmpresa')
								->join('distrito', 'empresa.idDistrito', '=', 'distrito.idDistrito')
								->where('cliente.idCliente', '=', $id_empresa);
	}

}