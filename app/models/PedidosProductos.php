<?php
class PedidosProductos extends Eloquent {
	protected $table = 'pedidos_productos';
	public $timestamps = false;

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\DistController@confirmar
	 *
	 */	
	public static function ultimo_conf($ult_pedido){
		return self::join('pedidos', 'pedidos.idPedido', '=', 'pedidos_productos.idPedido')
					->join('productos', 'productos.idProducto', '=', 'pedidos_productos.idProducto')
					->join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
					->where('productos.estado', '=', 1)
					->where('inventario_adm.Estado', '=', 1)
					->where('pedidos.idPedido', '=', $ult_pedido)
					->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\DistController@ver_detalle
	 * Pedidos\AdministradorController@postVerDetalle
	 * Pedidos\AdministradorController@postConfirmarPendiente
	 *
	 */	
	public static function detalle($id_pedido){
		return self::join('productos', 'productos.idProducto', '=', 'pedidos_productos.idProducto')
					->join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
					->where('productos.estado', '=', 1)
					->where('inventario_adm.Estado', '=', 1)
					->where('pedidos_productos.idPedido', '=', $id_pedido)
					->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\AdministradorController@getModificar
	 * Pedidos\DistController@modificar
	 *
	 */	
	public static function ver_modificar($id_pedido){
		return self::join('pedidos', 'pedidos_productos.idPedido', '=', 'pedidos.idPedido')
					->join('productos', 'pedidos_productos.idProducto', '=', 'productos.idProducto')
					->join('categoria_producto', 'categoria_producto.idCategoriaProducto', '=', 'productos.idCategoriaProducto')
					->join('inventario_adm', 'productos.idProducto', '=', 'inventario_adm.idProducto')
					->where('productos.estado', '=', 1)
					->where('inventario_adm.Estado', '=', 1)
					->where('pedidos.idPedido', '=', $id_pedido)
					->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Pedidos\AdministradorController@getModificar
	 * Pedidos\DistController@modificar
	 *
	 */	
	public static function prod_sob($id_pedido){
		return DB::select('SELECT * 
							FROM vitasalud.productos
							INNER JOIN vitasalud.categoria_producto 
							ON (productos.idCategoriaProducto = categoria_producto.idCategoriaProducto)
							INNER JOIN inventario_adm
							ON (inventario_adm.idProducto = productos.idProducto)
							WHERE productos.estado = 1 AND inventario_adm.Estado = 1 AND productos.idProducto NOT IN (SELECT idProducto FROM pedidos_productos WHERE idPedido = ?);', [$id_pedido]);
	}

	/**
	 * Productos que no estan en el inventario del distribuidor 'x'.
	 */
	public static function prod_no_inv($id_pedido, $id_distribuidor){
		// Consulta: Productos que están en inventario
		$object = DB::table('inventario_distribuidor')->select('idProducto')->distinct()->where('idDistribuidor', '=', $id_distribuidor)->get();
		// Array que contendrá solo los idProducto
		if(count($object) != 0){
			$array = array();
			foreach ($object as $value) {
				array_push($array, $value->idProducto);
			}
			// Retornamos productos que no estan en el inventario pero si en el pedido
			return PedidosProductos::whereNotIn('idProducto', $array)->where('idPedido', '=', $id_pedido)->get();
			// Retornamos productos que no estan en el inventario pero si en el pedido combinado con pedidos
			/* return PedidosProductos::join('pedidos', 'pedidos_productos.idPedido', '=', 'pedidos.idPedido')
									->whereNotIn('pedidos_productos.idProducto', $array)->where('pedidos_productos.idPedido', '=', $id_pedido)->get();*/
		}
		return PedidosProductos::where('idPedido', '=', $id_pedido)->get();
	}

}