<?php
class ProductoDistribuidor extends Eloquent {
	protected $table = 'producto_distribuidor';
	protected $primaryKey = 'idProductoDist';
	public $timestamps = false;

	public function categoriaProductoDistribuidor(){
		return $this->belongsTo('CategoriaProductoDistribuidor');
	}

	public function detalleCompras(){
		return $this->hasMany('DetComprasDist', 'idProductoCompraDist');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Compras\Distribuidor\HomeController@index
	 *
	 */
	public function scopeLista($query, $categoria){
		return $query->where('idCategoriaProductoDist', '=', $categoria)->where('EstadoProductoDist', '=', 1);
	}

}