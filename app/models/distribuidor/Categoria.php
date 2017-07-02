<?php
namespace Model\Distribuidor;

class Categoria extends \Eloquent{
	protected $table = 'categoria_producto_distribuidor';
	protected $primaryKey = 'idCategoriaProductoDist';
	public $timestamps = false;

	/**
	 * ...
	 * Muestra solo la lista de las categorías de los productos del distribuidor
	 * Utilizado en:
	 * Distribuidor\Gestion\ProductosController@index
	 *
	 */
	public static function lista(){
		return Categoria::where('Estado', '=', 1)->get();
	}

}