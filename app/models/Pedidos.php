<?php
class Pedidos extends Eloquent {
	protected $table = 'pedidos';
	protected $primaryKey = 'idPedido';
	public $timestamps = false;

	/**
	 * Lista de pedidos.
	 *
	 * Utilizado en:
	 * Pedidos\AdministradorController@getIndex
	 *
	 */
	public function scopeLista($query, $estado, $estado_oculto){
		return $query->join('distribuidores', 'pedidos.idDistribuidor', '=', 'distribuidores.idDistribuidor')
						->join('persona', 'distribuidores.idPersona', '=', 'persona.idPersona')
						->where('pedidos.EstadoPedido', '=', $estado)
						->orWhere('pedidos.EstadoPedido', '=', $estado_oculto);
	}

}