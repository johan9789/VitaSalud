<?php
class InventarioDistribuidor extends Eloquent {
	protected $table = 'inventario_distribuidor';
	protected $primaryKey = 'idinventario_distribuidor';
	public $timestamps = false;

	/**
	 * Obtiene el producto solicitado por código de barras. 
	 *
	 * Utilizado en:	 
	 * Ventas\HomeController@postIndex
	 * Ventas\HomeController@postProductos
	 *
	 */
	public function scopeProductos($query, $cod_barras){
		return $query->join('producto_distribuidor', 'inventario_distribuidor.idProductoDist', '=', 'producto_distribuidor.idProductoDist')
					->where('producto_distribuidor.CodBarrasDist', $cod_barras)
                    ->where('idDistribuidor', Session::get('id_dist'))
					->where('inventario_distribuidor.Estado', 1)
					->take(1);
	}

}