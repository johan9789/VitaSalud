<?php
class Compras extends Eloquent {
	protected $table = 'compras';
	protected $primaryKey = 'idCompra';
	public $timestamps = false;

	public function proveedor(){
		return $this->belongsTo('Proveedor', 'idProveedor');
	}

}