<?php
namespace Graphics;

use Date, DB;

class Venta {	
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getIndex
	 */
	public static function actual(){
		return DB::select("SELECT producto_distribuidor.NombreProductoDist, SUM(venta_detalle.Cantidad) AS cantidad_final FROM venta
					JOIN venta_detalle ON venta.idventa = venta_detalle.idventa
					JOIN producto_distribuidor ON producto_distribuidor.idProductoDist = venta_detalle.idProductoDist
					WHERE venta.Fecha LIKE ?
					GROUP BY producto_distribuidor.idProductoDist", [Date::current().'%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getIndex
	 */
	public static function actualIngresos(){
		return DB::select("SELECT producto_distribuidor.NombreProductoDist, SUM(venta_detalle.PrecioTotal) AS ingreso_total FROM venta
					JOIN venta_detalle ON venta.`idventa` = venta_detalle.`idventa`
					JOIN producto_distribuidor ON producto_distribuidor.`idProductoDist` = venta_detalle.`idProductoDist`
					WHERE venta.Fecha LIKE ?
					GROUP BY producto_distribuidor.`idProductoDist`", [Date::current().'%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getFechas
	 */
	public static function entreFechas($fecha_1, $fecha_2){
		return DB::select("SELECT producto_distribuidor.NombreProductoDist, SUM(venta_detalle.Cantidad) AS cantidad_final FROM venta
					JOIN venta_detalle ON venta.`idventa` = venta_detalle.`idventa`
					JOIN producto_distribuidor ON producto_distribuidor.`idProductoDist` = venta_detalle.`idProductoDist`
					WHERE venta.Fecha BETWEEN ? AND ?
					GROUP BY producto_distribuidor.`idProductoDist`", [$fecha_1, $fecha_2]);
	}	

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getFechas
	 */
	public static function ingresosEntreFechas($fecha_1, $fecha_2){
		return DB::select("SELECT producto_distribuidor.NombreProductoDist, SUM(venta_detalle.PrecioTotal) AS ingreso_total FROM venta
					JOIN venta_detalle ON venta.`idventa` = venta_detalle.`idventa`
					JOIN producto_distribuidor ON producto_distribuidor.`idProductoDist` = venta_detalle.`idProductoDist`
					WHERE venta.Fecha BETWEEN ? AND ?
					GROUP BY producto_distribuidor.`idProductoDist`", [$fecha_1, $fecha_2]);
	}	

}