<?php
class Categoriaproducto extends Eloquent{
	protected $table = 'categoria_producto';
	protected $primaryKey = 'idCategoriaProducto';
	public $timestamps = false;

	public function productos(){
		return $this->hasMany('Productos', 'idCategoriaProducto');
	}

	/**
	 * Lista de categorías con sus productos correspondientes.
	 *
	 * Utilizado en:
	 * Compras\Administrador\HomeController@index
	 *
	 */
	public function scopeTotal($query){
		return $query->where('Estado', 1)->with(['productos' => function($query){
					$query->where('estado', 1);
				}]);
	}

}