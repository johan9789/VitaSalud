<?php
namespace Model\Distribuidor;

class MovimientosDistribuidor extends \Eloquent {
	protected $table = 'movimientos_distribuidor';
	protected $primaryKey = 'idmovimientos_distribuidor';
	public $timestamps = false;

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Distribuidor\Gestion\InventarioController@getMovimientos
	 * Distribuidor\Gestion\InventarioController@postMovimientos
	 *
	 */
	public static function listamovimentos($id_distribuidor, $fecha_1, $fecha_2){
		return MovimientosDistribuidor::join('productos', 'movimientos_distribuidor.idProducto', '=', 'productos.idProducto')
									->where('movimientos_distribuidor.idDistribuidor', '=', $id_distribuidor)
									->whereBetween('movimientos_distribuidor.FechaMovimiento', array($fecha_1, $fecha_2))->get();
	}

}