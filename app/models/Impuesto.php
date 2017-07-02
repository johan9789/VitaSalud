<?php 
class Impuesto extends Eloquent {
	protected $table = 'impuesto';
	protected $primaryKey = 'idimpuesto';
	public $tiemstamps = false;

	/**
	 * IGV actual.
	 *
	 * Utilizado en:
	 * Compras\Administrador\HomeController@index
	 * Compras\Distribuidor\HomeController@index
	 * Ventas\HomeController@post_index
	 *
	 */
	public function scopeGetIGV($query){
		return $query->select('IGV')->first()->IGV;
	}

} 