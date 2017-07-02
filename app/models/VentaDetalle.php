<?php
class VentaDetalle extends Eloquent {
	protected $table = 'venta_detalle';
	public $timestamps = false;

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\ReportesController@post_detalle
	 * Ventas\Administrador\ReportesController@get_detalle
	 * Ventas\Administrador\ReportesController@get_excel
	 * Ventas\Distribuidor\ReportesController@post_detalle
	 * Ventas\Distribuidor\ReportesController@get_detalle
	 * Ventas\Distribuidor\ReportesController@get_excel
	 */
	public function scopeLista($query, $id_venta){
		return $query->join('venta', 'venta.idventa', '=', 'venta_detalle.idventa')
					->join('producto_distribuidor', 'producto_distribuidor.idProductoDist', '=', 'venta_detalle.idProductoDist')
					->where('venta.idventa', '=', $id_venta);
	}

}