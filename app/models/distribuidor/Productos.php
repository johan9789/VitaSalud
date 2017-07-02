<?php 
namespace Model\Distribuidor;

class Productos extends \Eloquent{
	protected $table = 'producto_distribuidor';
	protected $primaryKey = 'idProductoDist';
	public $timestamps = false;


	/**
	 * ...
	 * Muestra solo la lista de productos del distribuidor
	 * Utilizado en:
	 * Distribuidor\Gestion\ProductosController@index
	 *
	 */
	public static function lista(){
		return Productos::join('categoria_producto_distribuidor', 'producto_distribuidor.idCategoriaProductoDist', '=', 'categoria_producto_distribuidor.idCategoriaProductoDist')
						->where('producto_distribuidor.EstadoProductoDist', '=', 1)
						->orderBy('producto_distribuidor.idProductoDist')
						->get();
	}

}