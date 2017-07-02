<?php
class Cliente extends Eloquent {
	protected $table = 'cliente';
	protected $primaryKey = 'idCliente';
	public $timestamps = false;

	public function persona(){
		return $this->belongsTo('Persona', 'idPersona');
	}

	public function empresa(){
		return $this->belongsTo('Empresa', 'idEmpresa');
	}

	/**
	 * Lista de clientes.
	 *
	 * Utilizado en:
	 * Ventas\HomeController@getIndex
	 *
	 */
	public function scopeLista($query){
		return $query->join('persona', 'persona.idPersona', '=', 'cliente.idPersona')
						->where('persona.estado', '=', 1)
						->orderBy('persona.apellidos');
	}	

	/**
	 * Detalle del cliente.
	 *
	 * Utilizado en:
	 * Resources\ClientesController@show
	 *
	 */
	public function scopeDetalle($query, $id_cliente){
		return $query->join('persona', 'persona.idPersona', '=', 'cliente.idPersona')
						->join('distrito', 'persona.idDistrito', '=', 'distrito.idDistrito')
						->where('cliente.idCliente', '=', $id_cliente);
	}

	/**
	 * Clientes de personalidad natural
	 *
	 * Utilizado en:
	 * Gestion\ClientesController@getIndex
	 *
	 */
	public function scopeNatural($query){
		return $query->join('persona', 'cliente.idPersona', '=', 'persona.idPersona')
						->where('persona.estado', '=', 1)->get();
	}
	
	/**
	 * Clientes de personalidad jurídica
	 *
	 * Utilizado en:
	 * Gestion\ClientesController@getIndex
	 *
	 */
	public function scopeJuridica($query){
		return $query->join('empresa', 'cliente.idEmpresa', '=', 'empresa.idEmpresa')->get();
	}

}