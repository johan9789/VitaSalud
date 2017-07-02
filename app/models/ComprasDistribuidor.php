<?php
class ComprasDistribuidor extends Eloquent {
	protected $table = 'compras_distribuidor';
	protected $primaryKey = 'idCompraDist';
	public $timestamps = false;

	public function proveedor(){
		return $this->belongsTo('ProveedorDistribuidor', 'idProveedorDist');
	}

}