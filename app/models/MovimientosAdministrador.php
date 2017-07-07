<?php
class MovimientosAdministrador extends Eloquent {
	protected $table = 'movimientos_adm';
	protected $primaryKey = 'idMovimiento';
	public $timestamps = false;

	public function producto(){
	    return $this->belongsTo('Productos', 'idProductoMovimiento');
    }

    public function scopeEntreFechas($query, $fechaInicial, $fechaFinal){
        return $query->whereBetween('FechaMovimiento', [$fechaInicial, $fechaFinal])->with('producto');
    }

}