<?php
namespace Model\Distribuidor;

class MovimientosDistribuidor extends \Eloquent {
	protected $table = 'movimientos_distribuidor';
	protected $primaryKey = 'idmovimientos_distribuidor';
	public $timestamps = false;

    public function distribuidor(){
        return $this->belongsTo('Distribuidores', 'idDistribuidor');
	}

    public function producto(){
        return $this->belongsTo('DistProductos', 'idProductoDist');
	}

	/**
	 * ...
	 *
	 * Utilizado en:
	 * Distribuidor\Gestion\InventarioController@getMovimientos
	 * Distribuidor\Gestion\InventarioController@postMovimientos
	 *
	 */
	public static function listamovimentos($id_distribuidor, $fecha_1, $fecha_2){
		return MovimientosDistribuidor::join('producto_distribuidor', 'movimientos_distribuidor.idProductoDist', '=', 'producto_distribuidor.idProductoDist')
									->where('movimientos_distribuidor.idDistribuidor', '=', $id_distribuidor)
									->whereBetween('movimientos_distribuidor.FechaMovimiento', array($fecha_1, $fecha_2))->get();
	}

    public function scopeLista($query, $fecha1, $fecha2){
        return $query->whereBetween('FechaMovimiento', [$fecha1, $fecha2])->has('producto')->with('producto');
    }

}
