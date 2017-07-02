<?php
class Productos extends Eloquent {
	protected $table = 'productos';
	protected $primaryKey = 'idProducto';
	public $timestamps = false;

	public function categoriaProducto(){
		return $this->belongsTo('Categoriaproducto', 'idProducto');
	}

	public function detalleCompras(){
		return $this->hasMany('DetalleCompras', 'idProductoCompra');
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
	 * Gestion\ProductosController@getIndex
	 *
	 */
	public static function lista_prod(){
		return Productos::join('categoria_producto', 'productos.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
						->where('productos.estado', '=', 1)->get();

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
	 * Gestion\ProductosController@postFiltrarprodxcat
	 * Gestion\ProductosController@getCatalogo
	 *
	 */
	public static function lista_inventario(){
		return Productos::join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
						->where('productos.estado', '=', 1)
						->where('inventario_adm.Estado', '=', 1)
						->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\ProductosController@postFiltrarprodxcat
	 *
	 */
	public static function listaxcategoria($idCat){
		return Productos::join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
						->where('productos.estado', '=', 1)
						->where('productos.idCategoriaProducto', '=', $idCat)
						->where('inventario_adm.Estado', '=', 1)
						->groupBy('inventario_adm.idProducto');
	}

	/**
	 * Método para determinar que categorias se estan usando para con los respectivos productos
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getRegistroentrada
	 * Gestion\InventarioController@getKardex
	 *
	 */
	public static function prodcategoria(){
		return Productos::select('productos.idCategoriaProducto', 'categoria_producto.NombreCategoriaProducto')
							->join('categoria_producto', 'productos.idCategoriaProducto', '=', 'categoria_producto.idCategoriaProducto')
							->groupBy('productos.idCategoriaProducto');
	}

	/**
	 * Utilizado en:
	 * Gestion\InventarioController@postFiltrardatos	 
	 *
	 */
	public static function filtradokardex($id){
		return Productos::where('idProducto', '=', $id);
	}

}