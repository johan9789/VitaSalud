<?php
class CategoriaProductoDistribuidor extends Eloquent {
	protected $table = 'categoria_producto_distribuidor';
	protected $primaryKey = 'idCategoriaProductoDist';
	public $timestamps = false;

	public function productosDistribuidor(){
		return $this->hasMany('ProductoDistribuidor', 'idCategoriaProductoDist');
	}

	/**
	 * Lista de categorías con sus productos correspondientes.
	 *
	 * Utilizado en:
	 * Compras\Distribuidor\HomeController@index
	 *
	 */
	public function scopeTotal($query){
		return $query->where('Estado', 1)->with(['productosDistribuidor' => function($query){
			$query->where('EstadoProductoDist', 1);
		}]);
	}

}