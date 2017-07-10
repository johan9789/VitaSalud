<?php
class Distribuidores extends Eloquent {
    protected $table = 'distribuidores';
    protected $primaryKey = 'idDistribuidor';
    public $timestamps = false;

    public function movimientos(){
        return $this->hasMany('DistMovimientos', 'idDistribuidor');
    }

	/**
	 * ...
	 *
	 * Utilizado en:
	 * MiCuentaController@getIndex
	 * MiOficinaController@getIndex
	 *
	 */
	public static function misComisiones($id_dist){
		return Comisiones::where('idDistribuidor', '=', $id_dist)->where('Estado', '=', 1);
	}

}