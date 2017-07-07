<?php 
class Inventario extends Eloquent {
	protected $table = 'inventario_adm';
	protected $primaryKey = 'idProducto';
	public $timestamps = false;

	public function producto(){
	    return $this->belongsTo('Productos', 'idProducto');
    }

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getIndex
	 *
	 */
	public function scopeListaStock($query){
	    return $query->select(DB::raw('*, SUM(inventario_adm.Existencia) as stock'))
                    ->join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
                    ->join('categoria_producto', 'categoria_producto.idCategoriaProducto', '=', 'productos.idCategoriaProducto')
                    ->where('productos.estado', 1)
                    ->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getKardex
	 *
	 */
	public function scopeListaProductos($query){
		return $query->join('productos', 'inventario_adm.idProducto', '=', 'productos.idProducto')
					->where('productos.estado', 1)->groupBy('inventario_adm.idProducto');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Gestion\InventarioController@getIndex
	 * Gestion\InventarioController@getRegprodreciente
	 *
	 */
	public static function prodRecientes(){
        return Productos::select('idProducto')->whereNotIn('idProducto', function($q){
                    $q->select('idProducto')->from('inventario_adm');
                })->where('estado', 1);
	}

	/**
	 * Esta función retorna el producto 
	 */
	public static function filtradoSubquery($id){
		return DB::select("SELECT * 
							FROM (SELECT * FROM inventario_adm ORDER BY idInventario DESC) AS inv 
							INNER JOIN productos p
								ON(inv.idProducto = p.idProducto)
							WHERE p.estado = 1
								AND p.idProducto = $id
							GROUP BY inv.idProducto")[0];
	}

	/**
	 * Para la solución del problema anterior, lo que se hizo es unir 2 tablas con consultas similares, lo cual el primer select
	 * nos devuelve todos los campos con idDistribuidorMovimiento null.
	 * El segundo select nos devuelve campos que si tienen idDistribuidorMovimiento, al tener este id realizamos un inner join con
	 * la tabla persona y capturamos los nombres y apellidos
	 */
	public static function movimientosSubquery($fecha_1, $fecha_2){
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

}