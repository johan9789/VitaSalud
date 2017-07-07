<?php
class Productos extends Eloquent {
	protected $table = 'productos';
	protected $primaryKey = 'idProducto';
	public $timestamps = false;

	public function categoriaProducto(){
		return $this->belongsTo('Categoriaproducto', 'idCategoriaProducto');
	}

	public function detalleCompras(){
		return $this->hasMany('DetalleCompras', 'idProductoCompra');
	}

	public function inventarios(){
	    return $this->hasMany('Inventario', 'idProducto');
    }

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\ProductosController@postRegistrarprod
	 * Pedidos\DistController@realizar
	 *
	 */
	public static function lista(){
		return Productos::join('categoria_producto', 'productos.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
						->join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
						->where('productos.estado', '=', 1)
						->where('inventario_adm.Estado', '=', 1)
						->groupBy('inventario_adm.idProducto');

	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\AdministradorController@getModificar
	 *
	 */
	public static function lista_total(){
		return Productos::join('categoria_producto', 'productos.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
						->where('productos.estado', '=', 1)->orderBy('productos.idProducto')->count();
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\DistController@realizar
	 * Pedidos\DistController@modificar
	 *
	 */
	public static function lista_total2(){
		return Productos::join('categoria_producto', 'productos.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
						->join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
						->where('productos.estado', '=', 1)
						->where('inventario_adm.Estado', '=', 1)
						->groupBy('inventario_adm.idProducto')->get();
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\ProductosController@getFiltrarprodxcat
	 * Gestion\ProductosController@getCatalogo
	 *
	 */
	public function scopeListaInventario($query){
        return $query->where('estado', 1)->whereHas('inventarios', function($q){
            $q->where('Estado', 1)->orderBy('idInventario', 'desc');
        })->with('inventarios');
	}

}