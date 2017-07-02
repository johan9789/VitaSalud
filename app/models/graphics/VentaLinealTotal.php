<?php 
namespace Graphics;

use Date, DB;

class VentaLinealTotal {
	private static $query = "SELECT SUM(Total) AS total_final  FROM venta WHERE Fecha LIKE ? GROUP BY Total AND Fecha";

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function enero($año){
		return DB::select(self::$query, [$año.'-01%']);
	}

	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function febrero($año){
		return DB::select(self::$query, [$año.'-02%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function marzo($año){
		return DB::select(self::$query, [$año.'-03%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */	
	public static function abril($año){
		return DB::select(self::$query, [$año.'-04%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function mayo($año){
		return DB::select(self::$query, [$año.'-05%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function junio($año){
		return DB::select(self::$query, [$año.'-06%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function julio($año){
		return DB::select(self::$query, [$año.'-07%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function agosto($año){
		return DB::select(self::$query, [$año.'-08%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function septiembre($año){
		return DB::select(self::$query, [$año.'-09%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function octubre($año){
		return DB::select(self::$query, [$año.'-10%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function noviembre($año){
		return DB::select(self::$query, [$año.'-11%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLineal
	 */
	public static function diciembre($año){
		return DB::select(self::$query, [$año.'-12%']);
	}
	
	/**
	 * ...
	 *
	 * Utilizado en: 
	 * Ventas\Administrador\GraficosController@getLinealAnual
	 */	
	public static function anual($año){
		return DB::select(self::$query, [$año.'%']);
	}

}