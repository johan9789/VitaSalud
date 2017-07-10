<?php
namespace Model\Distribuidor;

use DB;

class Inventario extends \Eloquent {
    protected $table = 'inventario_distribuidor';
    protected $primaryKey = 'idProducto';
    public $timestamps = false;

    public static function lista($id_distribuidor){
        return Inventario::join('productos', 'inventario_distribuidor.idProducto', '=', 'productos.idProducto')
                        ->where('idDistribuidor', '=', $id_distribuidor);
    }

    /**
     * ...
     *
     * Utilizado en:
     * Distribuidor\Gestion\InventarioController@getIndex
     *
     */
    public function scopeStock($query, $idDistribuidor){
        return $query->select(DB::raw('*, SUM(inventario_distribuidor.Existencia) as stock'))
                    ->join('producto_distribuidor', 'inventario_distribuidor.idProductoDist', '=', 'producto_distribuidor.idProductoDist')
                    ->join('categoria_producto_distribuidor', 'categoria_producto_distribuidor.idCategoriaProductoDist', '=', 'producto_distribuidor.idCategoriaProductoDist')
                    ->where('producto_distribuidor.EstadoProductoDist', 1)
                    ->where('inventario_distribuidor.idDistribuidor', $idDistribuidor)
                    ->groupBy('inventario_distribuidor.idProductoDist');
    }

	/*Productos que estan en el inventario del distrinbuidor x*/
	public static function prod_en_inv($id_pedido, $id_distribuidor){

		//Consulta: Productos que están en pedidos_productos de un determinado pedido
		$object = \DB::table('pedidos_productos')->select('idProducto')->distinct()->where('idPedido', '=', $id_pedido)->get();
		//Array que contendrá solo los idProducto
		
		if(count($object) != 0){
			$array = array();
			foreach ($object as $value) {
				array_push($array, $value->idProducto);
			}

			//Retornamos productos que estan en el inventario
			/*return Inventario::whereIn('idProducto', $array)->where('idDistribuidor', '=', $id_distribuidor)->get();*/
			//Retornamos productos que estan en el inventario con los pedidos
			return Inventario::join('pedidos_productos', 'inventario_distribuidor.idProducto', '=', 'pedidos_productos.idProducto')
							->whereIn('inventario_distribuidor.idProducto', $array)
							->where('pedidos_productos.idPedido', '=', $id_pedido)
							->where('inventario_distribuidor.idDistribuidor', '=', $id_distribuidor)->get();
		}
		$arreglo_vacio = array();
		
		return $arreglo_vacio;
	}

}