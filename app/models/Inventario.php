<?php 
class Inventario extends Eloquent {
	protected $table = 'inventario_adm';
	protected $primaryKey = 'idProducto';
	public $timestamps = false;

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getInventarioinicial
	 *
	 */
	public static function lista(){
		return Inventario::join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
					->where('productos.estado', '=', 1);
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getIndex
	 *
	 */
	public static function lista_stock(){
		return DB::select("SELECT *, SUM(i.Existencia) as stock  
							FROM inventario_adm i
							INNER JOIN productos p
								ON(i.idProducto = p.idProducto)
							INNER JOIN categoria_producto c
								ON(c.idCategoriaProducto = p.idCategoriaProducto)
							WHERE p.estado = 1
							GROUP BY i.idProducto");
	}

	/**
	 * ...
	 */
	public static function lista_existencia(){
		return Inventario::join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
					->where('productos.estado', '=', 1)
					->where('inventario_adm.Estado', '=', 1)->orderBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getRegistroentrada
	 * Gestion\InventarioController@getKardex
	 *
	 */
	public static function lista_productos(){
		return Inventario::join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
					->where('productos.estado', '=', 1)->groupBy('inventario_adm.idProducto');
	} 

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getInventarioinicial
	 *
	 */
	public static function lista_prod_actuales(){
		return DB::select("SELECT idProducto FROM productos WHERE estado = 1");
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getIndex
	 * Gestion\InventarioController@getRegprodreciente
	 *
	 */
	public static function lista_prod_recientes(){
		return DB::select("SELECT p.idProducto FROM productos p WHERE p.idProducto NOT IN (SELECT i.idProducto FROM inventario_adm i) AND p.estado = 1");
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@postFiltrarinventario	 
	 *
	 */
	public static function filtradoxprod($id){
		return Inventario::where('idProducto', '=', $id)->orderBy('idInventario', 'desc')->get()[0];
	}



	/**
	 * Esta función retorna el producto 
	 */
	public static function filtradoxprod_ultcosto($id){
		return DB::select("SELECT * 
							FROM (SELECT * FROM inventario_adm ORDER BY idInventario DESC) AS inv 
							INNER JOIN productos p
								ON(inv.idProducto = p.idProducto)
							WHERE p.estado = 1
								AND p.idProducto = $id
							GROUP BY inv.idProducto")[0];
	}

	/**
	 * ...
	 */
	public static function Entradas_Salidas($id){
		return DB::select("SELECT Entrada, Salida FROM inventario WHERE idProducto = ".$id)[0];
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getMovimientos	 
	 * Gestion\InventarioController@postMovimientos
	 * Gestion\InventarioController@getKardex
	 *
	 */
	public static function Movimientos($fecha_1, $fecha_2){
		return DB::select("SELECT DATE_FORMAT(m.FechaMovimiento, '%d-%m-%Y') AS FechaMovimiento, 
									p.NombreProducto, 
									m.CantidadMovimiento, 
									m.CostoMovimiento, 
									m.TotalCosto,
									m.TipoMovimiento, 
									m.RegistradoPor 
									FROM movimientos_adm m INNER JOIN productos p 
									WHERE m.idProductoMovimiento = p.idProducto 
									AND m.FechaMovimiento BETWEEN '$fecha_1' AND '$fecha_2' 
									ORDER BY m.idMovimiento");
	}
	
	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getKardex
	 *
	 */
	public static function kardex($fecha_1, $fecha_2){
		return DB::select("SELECT DATE_FORMAT(m.FechaMovimiento, '%d-%m-%Y') AS FechaMovimiento, 
									p.NombreProducto, 
									m.CantidadMovimiento, 
									m.CostoMovimiento, 
									m.TotalCosto,
									m.TipoMovimiento, 
									m.RegistradoPor 
									FROM movimientos_adm m INNER JOIN productos p 
									WHERE m.idProductoMovimiento = p.idProducto 
									AND m.FechaMovimiento BETWEEN '$fecha_1' AND '$fecha_2' 
									ORDER BY m.idMovimiento");
	}

	/**
	 * Para la solución del problema anterior, lo que se hizo es unir 2 tablas con consultas similares, lo cual el primer select
	 * nos devuelve todos los campos con idDistribuidorMovimiento null.
	 * El segundo select nos devuelve campos que si tienen idDistribuidorMovimiento, al tener este id realizamos un inner join con
	 * la tabla persona y capturamos los nombres y apellidos
	 */
	public static function Movimientos2($fecha_1, $fecha_2){
		return DB::select("SELECT m.idMovimiento, 
									DATE_FORMAT(m.FechaMovimiento, '%d-%m-%Y') AS FechaMovimiento, 
									p.NombreProducto, 
									m.CantidadMovimiento, 
									m.PrecioMovimiento, 
									m.ImporteMovimiento,
									m.TipoMovimiento,
									m.idDistribuidorMovimiento,
									m.RegistradoPor,
									'' AS persona 
									FROM movimiento m 
									INNER JOIN productos p
										ON(m.idProductoMovimiento = p.idProducto)
									WHERE m.idDistribuidorMovimiento IS NULL
									AND m.FechaMovimiento BETWEEN '$fecha_1' AND '$fecha_2'
									UNION
							SELECT 	m.idMovimiento, 
									DATE_FORMAT(m.FechaMovimiento, '%d-%m-%Y') AS FechaMovimiento, 
									p.NombreProducto, 
									m.CantidadMovimiento, 
									m.PrecioMovimiento, 
									m.ImporteMovimiento,
									m.TipoMovimiento,
									m.idDistribuidorMovimiento,
									m.RegistradoPor,
									CONCAT(per.Nombres, ' ', per.Apellidos) AS persona 
									FROM movimiento m 
									INNER JOIN productos p
										ON(m.idProductoMovimiento = p.idProducto)
									INNER JOIN distribuidores d
										ON(m.idDistribuidorMovimiento = d.idDistribuidor)
									INNER JOIN persona per
										ON(d.idPersona = per.idPersona)
									WHERE m.idDistribuidorMovimiento IS NOT NULL
									AND m.FechaMovimiento BETWEEN '$fecha_1' AND '$fecha_2'
							GROUP BY idMovimiento
							ORDER BY idMovimiento");
	}
	
	/**
	 * Registro de inventario por idInventario e idProducto
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@postActualizarDetalle
	 *
	 */
	public static function registro_detalle($idInventario, $idProducto){
		return Inventario::whereRaw('Estado = ? AND MD5(idInventario) = ? AND MD5(idProducto) = ?', [1, $idInventario, $idProducto])->get();
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@postAgruparExistencia
	 * Gestion\InventarioController@getPrueba
	 *
	 */
	public static function reg_exist_agrup($idProducto){
		return Inventario::whereRaw('Estado = ? AND MD5(idProducto) = ?', [1, $idProducto]);
	}

}