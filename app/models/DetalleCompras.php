<?php
class DetalleCompras extends Eloquent {
	protected $table = 'detalle_compras';
	protected $primaryKey = 'idDetCompra';
	public $timestamps = false;

	public function producto(){
		return $this->belongsTo('Productos', 'idProductoCompra');
	}

}