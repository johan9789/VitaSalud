<?php
namespace Graphics;

use Date, DB;

class VentaLineal {
	public static $query = "SELECT SUM(`venta_detalle`.`Cantidad`) AS cantidad_final 
							FROM venta
							JOIN venta_detalle ON venta.`idventa` = venta_detalle.`idventa`
							JOIN producto_distribuidor ON producto_distribuidor.`idProductoDist` = venta_detalle.`idProductoDist`
							WHERE venta.Fecha LIKE ?
							GROUP BY producto_distribuidor.`idProductoDist` AND venta_detalle.`Cantidad`";

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function enero($year){
		return DB::select(self::$query, [$year.'-01%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function febrero($year){
		return DB::select(self::$query, [$year.'-02%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function marzo($year){
		return DB::select(self::$query, [$year.'-03%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function abril($year){
		return DB::select(self::$query, [$year.'-04%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function mayo($year){
		return DB::select(self::$query, [$year.'-05%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function junio($year){
		return DB::select(self::$query, [$year.'-06%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function julio($year){
		return DB::select(self::$query, [$year.'-07%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function agosto($year){
		return DB::select(self::$query, [$year.'-08%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function septiembre($year){
		return DB::select(self::$query, [$year.'-09%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function octubre($year){
		return DB::select(self::$query, [$year.'-10%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function noviembre($year){
		return DB::select(self::$query, [$year.'-11%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function diciembre($year){
		return DB::select(self::$query, [$year.'-12%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLinealAnual
	 */
	public static function anual($year){
		return DB::select(self::$query, [$year.'%']);
	}

}